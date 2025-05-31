import fitz
from typing import Dict, Any, List, Tuple
import tempfile
import os
import logging
import time
import shutil

from flask_app.app.services.plagiarism_checker_service import PlagiarismCheckerService

class PDFProcessor:
    def __init__(self, embedding_model: str):
        self.plagiarism_service = PlagiarismCheckerService(embedding_model)
        self.colors = {
            'critical': {  # 75-100%
                'bg': (0.984, 0.737, 0.737),  # Red
                'text': (0.8, 0.2, 0.2)
            },
            'high': {  # 50-74%
                'bg': (1.0, 0.847, 0.737),  # Orange
                'text': (0.8, 0.4, 0.0)
            },
            'moderate': {  # 25-49%
                'bg': (0.984, 0.953, 0.737),  # Yellow
                'text': (0.6, 0.6, 0.0)
            },
            'low': {  # 1-24%
                'bg': (0.859, 0.969, 0.859),  # Green
                'text': (0.0, 0.5, 0.0)
            }
        }
        self.fallback_fonts = ["helv", "times", "cour"]

    def _extract_paragraphs(self, pdf_path: str) -> Tuple[Dict[str, str], Dict[str, List[Dict[str, Any]]]]:
        """Extract text with precise word-level coordinates"""
        paragraphs = {}
        word_positions = {}
        doc = fitz.open(pdf_path)
        paragraph_index = 0
        current_paragraph = ""
        current_words = []

        for page_num in range(len(doc)):
            page = doc[page_num]
            blocks = page.get_text("words")  # Get word-level data
            
            for word_info in blocks:
                x0, y0, x1, y1 = word_info[0:4]  # Coordinates
                word = word_info[4]  # Actual word
                
                # Store word position data
                current_words.append({
                    "text": word,
                    "page": page_num,
                    "bbox": [x0, y0, x1, y1]
                })
                
                current_paragraph += word + " "
                
                # Create new paragraph when reaching size limit or end of semantic block
                if len(current_paragraph) >= 2000 or self._is_paragraph_break(word):
                    if current_paragraph.strip():
                        key = f"paragraph_{paragraph_index}"
                        paragraphs[key] = current_paragraph.strip()
                        word_positions[key] = current_words.copy()
                        paragraph_index += 1
                        current_paragraph = ""
                        current_words = []

        # Handle remaining text
        if current_paragraph.strip():
            key = f"paragraph_{paragraph_index}"
            paragraphs[key] = current_paragraph.strip()
            word_positions[key] = current_words

        doc.close()
        return paragraphs, word_positions

    def _is_paragraph_break(self, word: str) -> bool:
        """Detect natural paragraph breaks"""
        return word.strip().endswith(('.', '!', '?', ':"', '."', '!"', '?"'))

    def process_pdf(self, pdf_file) -> Tuple[str, Dict[str, Any]]:
        try:
            temp_input = tempfile.NamedTemporaryFile(delete=False, suffix='.pdf')
            pdf_file.save(temp_input.name)
            
            # Extract text with coordinates
            paragraphs, word_positions = self._extract_paragraphs(temp_input.name)
            
            # Get plagiarism results
            report = self.plagiarism_service.check_plagiarism_content(paragraphs)
            
            # Add word position data to report
            self._enrich_report_with_positions(report, word_positions)
            
            return temp_input.name, report
        except Exception as e:
            logging.exception("PDF processing failed")
            raise Exception(f"PDF processing failed: {str(e)}")

    def _enrich_report_with_positions(self, report: Dict[str, Any], word_positions: Dict[str, List[Dict[str, Any]]]):
        """Add word position data to plagiarism results"""
        for paragraph in report['data']['paragraphs']:
            para_id = paragraph['id']
            if para_id in word_positions:
                paragraph['word_positions'] = word_positions[para_id]

    def _get_highlight_color(self, similarity: float) -> Dict[str, Tuple[float, float, float]]:
        """Get colors based on similarity percentage - Turnitin style"""
        if similarity >= 75:
            return self.colors['critical']
        elif similarity >= 50:
            return self.colors['high']
        elif similarity >= 25:
            return self.colors['moderate']
        elif similarity > 0:
            return self.colors['low']
        return None

    def _get_safe_font(self, font_name: str) -> fitz.Font:
        try:
            return fitz.Font(font_name)
        except:
            for fallback in self.fallback_fonts:
                try:
                    return fitz.Font(fallback)
                except:
                    continue
            return fitz.Font("helv")

    def _highlight_pdf(self, input_path: str, plagiarism_results: List[Dict[str, Any]], 
                      paragraph_block_mapping: Dict[str, List[Dict[str, Any]]]) -> str:
        """Create highlighted PDF with source information - Turnitin style"""
        temp_output = tempfile.NamedTemporaryFile(delete=False, suffix='_highlighted.pdf')
        result_map = {result['id']: result for result in plagiarism_results}
        doc = fitz.open(input_path)

        for paragraph_id, blocks in paragraph_block_mapping.items():
            if paragraph_id not in result_map:
                continue
                
            result = result_map[paragraph_id]
            similarity = result['similarity_percentage']
            colors = self._get_highlight_color(similarity)
            if not colors:
                continue

            sources = result.get('sources', [])
            for block in blocks:
                page = doc[block['page']]
                
                # Create highlight with Turnitin-style colors
                rect = fitz.Rect(block['bbox'])
                annot = page.add_highlight_annot(rect)
                annot.set_colors(stroke=colors['text'], fill=colors['bg'])
                annot.set_opacity(0.4)  # Turnitin-like transparency
                
                # Add source information popup
                if sources:
                    source = sources[0]
                    popup_text = (
                        f"Similarity: {similarity:.1f}%\n"
                        f"Source: {source['title']}\n"
                        f"Document ID: {source['document_id']}"
                    )
                    
                    info = {
                        "title": "Similarity Match",
                        "subject": f"{similarity:.1f}% match",
                        "content": popup_text
                    }
                    
                    annot.set_info(info)
                    annot.update()

        doc.save(temp_output.name, garbage=4, deflate=True, clean=True)
        doc.close()
        return temp_output.name
