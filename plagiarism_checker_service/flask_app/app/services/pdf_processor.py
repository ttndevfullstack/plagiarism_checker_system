import fitz
import json
from typing import Dict, Any, List, Tuple
import tempfile
import os

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
            paragraphs = self._extract_paragraphs(temp_input.name)
            report = self.plagiarism_service.check_plagiarism_content(paragraphs)
            
            # Create highlighted PDF
            output_path = self._highlight_pdf(temp_input.name, report['data']['paragraphs'])
            
            return output_path, report
        except Exception as e:
            raise Exception(f"PDF plagiarism check failed: {str(e)}")
        finally:
            # Clean up temp input file
            if 'temp_input' in locals() and os.path.exists(temp_input.name):
                os.unlink(temp_input.name)

    def _extract_paragraphs(self, pdf_path: str) -> Dict[str, str]:
        """Extract text from PDF by paragraphs with unique keys"""
        paragraphs = {}
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
                    
                    if text.strip():
                        # Create unique key for each paragraph
                        key = f"page_{page_num}_block_{block_num}"
                        paragraphs[key] = text.strip()
        
        doc.close()
        return paragraphs

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
        """Add bold colored background highlights to paragraphs"""
        temp_output = tempfile.NamedTemporaryFile(delete=False, suffix='_highlighted.pdf')
        result_map = {result['id']: result for result in plagiarism_results}
        doc = fitz.open(input_path)
        
        # Enhanced color definitions with stronger RGB values
        bold_colors = {
            'high-risk': {
                'bg': (1.0, 0.8, 0.8),     # Stronger Red (originally FFEBEE)
                'border': (0.78, 0.16, 0.16)  # C62828
            },
            'moderate-risk': {
                'bg': (1.0, 0.85, 0.7),    # Stronger Orange (originally FFF3E0)
                'border': (0.94, 0.42, 0.0)   # EF6C00
            },
            'low-risk': {
                'bg': (1.0, 0.95, 0.6),    # Stronger Yellow (originally FFF9C4)
                'border': (0.98, 0.66, 0.15)  # F9A825
            },
            'original': {
                'bg': (0.8, 1.0, 0.8),     # Stronger Green (originally E8F5E9)
                'border': (0.18, 0.49, 0.2)   # 2E7D32
            }
        }

        def get_bold_color(similarity: float):
            if similarity > 85: return bold_colors['high-risk']
            elif similarity > 65: return bold_colors['moderate-risk']
            elif similarity > 30: return bold_colors['low-risk']
            return bold_colors['original']

        for page_num in range(len(doc)):
            page = doc[page_num]
            blocks = page.get_text("dict")["blocks"]
            
            for block_num, block in enumerate(blocks):
                if "lines" not in block:
                    continue
                
                key = f"page_{page_num}_block_{block_num}"
                if key not in result_map:
                    continue
                
                similarity = result_map[key]['similarity_percentage']
                colors = get_bold_color(similarity)
                
                # Combine all spans in block to create one rectangle
                block_rect = None
                for line in block["lines"]:
                    for span in line["spans"]:
                        rect = fitz.Rect(span["bbox"])
                        block_rect = rect if block_rect is None else block_rect | rect
                
                # Add bold background highlight
                if block_rect:
                    # More noticeable padding
                    block_rect += (-3, -3, 3, 3)
                    
                    highlight = page.add_rect_annot(block_rect)
                    highlight.set_colors(fill=colors['bg'])
                    highlight.set_opacity(0.5)  # Increased from 0.3 to 0.5
                    
                    # Thicker border in contrasting color
                    highlight.set_border(width=1.0, dashes=None)  # Solid line
                    highlight.set_colors(stroke=colors['border'])
                    highlight.update()
        
        # Optimize the PDF
        doc.save(temp_output.name, garbage=4, deflate=True, clean=True)
        doc.close()
        return temp_output.name