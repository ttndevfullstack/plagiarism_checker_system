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

    def _highlight_pdf(self, input_path: str, plagiarism_results: List[Dict[str, Any]]) -> str:
        """Add text highlights to sentences based on plagiarism results"""
        temp_output = tempfile.NamedTemporaryFile(delete=False, suffix='_highlighted.pdf')
        result_map = {result['id']: result for result in plagiarism_results}
        doc = fitz.open(input_path)

        # Simplified color definitions for text highlighting
        highlight_colors = {
            'high-risk': (1.0, 0.7, 0.7),      # Red highlight
            'moderate-risk': (1.0, 0.8, 0.86),  # Orange highlight
            'low-risk': (1.0, 1.0, 0.7),        # Yellow highlight
        }

        def get_highlight_color(similarity: float):
            if similarity > 85: return highlight_colors['high-risk']
            elif similarity > 65: return highlight_colors['moderate-risk']
            elif similarity > 30: return highlight_colors['low-risk']
            return None  # No highlight for original content

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
                    if (highlight_color is None):
                        continue
                    print(f"{similarity} - {highlight_color} - {sentence}")

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