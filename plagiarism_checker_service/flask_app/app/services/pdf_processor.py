import fitz
import json
from typing import Dict, Any, List, Tuple
import tempfile
import os
import re

from flask_app.app.services.plagiarism_checker_service import PlagiarismCheckerService

class PDFProcessor:
    def __init__(self, embedding_model: str):
        self.plagiarism_service = PlagiarismCheckerService(embedding_model)
        # Colors in RGBA format (values between 0 and 1)
        self.colors = {
            'high-risk': {
                'bg': (1.0, 0.92, 0.92),     # FFEBEE (Red)
                'text': (0.78, 0.16, 0.16)    # C62828
            },
            'moderate-risk': {
                'bg': (1.0, 0.95, 0.88),     # FFF3E0 (Orange)
                'text': (0.94, 0.42, 0.0)     # EF6C00
            },
            'low-risk': {
                'bg': (1.0, 0.98, 0.77),     # FFF9C4 (Yellow)
                'text': (0.98, 0.66, 0.15)    # F9A825
            },
            'original': {
                'bg': (0.91, 0.96, 0.91),    # E8F5E9 (Green)
                'text': (0.18, 0.49, 0.2)    # 2E7D32
            }
        }
        # List of fallback fonts in order of preference
        self.fallback_fonts = ["helv", "times", "cour"]

    def process_pdf(self, pdf_file) -> Tuple[str, Dict[str, Any]]:
        """Process PDF file and return the plagiarism report and highlighted PDF path"""
        try:
            # Create temp file for the uploaded PDF
            temp_input = tempfile.NamedTemporaryFile(delete=False, suffix='.pdf')
            pdf_file.save(temp_input.name)
            
            # Extract text and check plagiarism
            sentences = self._extract_sentences(temp_input.name)
            # return sentences
            report = self.plagiarism_service.check_plagiarism_content(sentences)
            
            # Create highlighted PDF
            output_path = self._highlight_pdf(temp_input.name, report['data']['paragraphs'])
            
            return output_path, report
        except Exception as e:
            raise Exception(f"PDF plagiarism check failed: {str(e)}")
        finally:
            # Clean up temp input file
            if 'temp_input' in locals() and os.path.exists(temp_input.name):
                os.unlink(temp_input.name)

    def _split_into_sentences(self, text: str) -> List[str]:
        """Split text into sentences using regex"""
        # Handle common abbreviations and special cases
        text = re.sub(r'([A-Z]\.)(?=[A-Z]\.)', r'\1|', text)  # Handle initials
        text = re.sub(r'(Mr\.|Mrs\.|Dr\.|Prof\.|Sr\.|Jr\.|vs\.|etc\.)', r'\1|', text)
        
        # Split into sentences
        sentences = re.split(r'(?<=[.!?])\s+(?=[A-Z])', text)
        
        # Remove the temporary marks and clean sentences
        sentences = [s.replace('|', '.').strip() for s in sentences if s.strip()]
        return sentences

    def _extract_sentences(self, pdf_path: str) -> Dict[str, str]:
        """Extract text from PDF by sentences with unique keys"""
        sentences = {}
        doc = fitz.open(pdf_path)
        
        for page_num in range(len(doc)):
            page = doc[page_num]
            blocks = page.get_text("dict")["blocks"]
            
            for block_num, block in enumerate(blocks):
                if "lines" in block:
                    text = ""
                    for line in block["lines"]:
                        for span in line["spans"]:
                            text += span["text"]
                        text += " "
                    
                    text = text.strip()
                    if text:
                        block_sentences = self._split_into_sentences(text)
                        for sent_num, sentence in enumerate(block_sentences):
                            if sentence.strip():
                                key = f"page_{page_num}_block_{block_num}_sent_{sent_num}"
                                sentences[key] = sentence.strip()
        
        doc.close()
        return sentences

    def _get_highlight_color(self, similarity: float) -> Dict[str, Tuple[float, float, float]]:
        """Get highlight color based on similarity percentage"""
        if similarity > 85:
            return self.colors['high-risk']
        elif similarity > 65:
            return self.colors['moderate-risk']
        elif similarity > 30:
            return self.colors['low-risk']
        return self.colors['original']

    def _get_safe_font(self, font_name: str) -> fitz.Font:
        """Get a safe font to use, falling back to defaults if needed"""
        try:
            # First try the requested font
            return fitz.Font(font_name)
        except:
            # Try fallback fonts in order
            for fallback in self.fallback_fonts:
                try:
                    return fitz.Font(fallback)
                except:
                    continue
            # If all else fails, use the default font
            return fitz.Font("helv")

    def _highlight_pdf(self, input_path: str, plagiarism_results: List[Dict[str, Any]]) -> str:
        """Add text highlights to sentences based on plagiarism results"""
        temp_output = tempfile.NamedTemporaryFile(delete=False, suffix='_highlighted.pdf')
        result_map = {result['id']: result for result in plagiarism_results}
        doc = fitz.open(input_path)

        # Simplified color definitions for text highlighting
        highlight_colors = {
            'high-risk': (1.0, 0.7, 0.7),      # Red highlight
            'moderate-risk': (1.0, 0.85, 0.7),  # Orange highlight
            'low-risk': (1.0, 1.0, 0.7),        # Yellow highlight
            'original': (0.7, 1.0, 0.7)         # Green highlight
        }

        def get_highlight_color(similarity: float):
            if similarity > 85: return highlight_colors['high-risk']
            elif similarity > 65: return highlight_colors['moderate-risk']
            elif similarity > 30: return highlight_colors['low-risk']
            return highlight_colors['original']

        for page_num in range(len(doc)):
            page = doc[page_num]
            blocks = page.get_text("dict")["blocks"]
            
            processed_sentences = set()  # Track processed sentences to avoid duplicates
            
            for block_num, block in enumerate(blocks):
                if "lines" not in block:
                    continue

                # Extract text from block
                text = ""
                for line in block["lines"]:
                    for span in line["spans"]:
                        text += span["text"]
                    text += " "
                text = text.strip()

                if not text:
                    continue

                # Split block text into sentences and process each
                sentences = self._split_into_sentences(text)
                for sent_num, sentence in enumerate(sentences):
                    key = f"page_{page_num}_block_{block_num}_sent_{sent_num}"
                    
                    if key not in result_map or sentence in processed_sentences:
                        continue

                    processed_sentences.add(sentence)
                    similarity = result_map[key]['similarity_percentage']
                    highlight_color = get_highlight_color(similarity)

                    # Find and highlight this sentence
                    text_instances = page.search_for(sentence)
                    for inst in text_instances:
                        highlight = page.add_highlight_annot(inst)
                        highlight.set_colors(stroke=highlight_color)
                        highlight.set_opacity(0.5)
                        highlight.update()

        # Save and optimize the PDF
        doc.save(temp_output.name, garbage=4, deflate=True, clean=True)
        doc.close()
        return temp_output.name