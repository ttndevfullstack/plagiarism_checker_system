import fitz
from typing import Dict, Any, List, Tuple
import tempfile
import os
import logging

from flask_app.app.services.plagiarism_checker_service import PlagiarismCheckerService

class PDFProcessor:
    def __init__(self, embedding_model: str):
        self.plagiarism_service = PlagiarismCheckerService(embedding_model)
        self.colors = {
            'high-risk': {
                'bg': (1.0, 0.92, 0.92),
                'text': (0.78, 0.16, 0.16)
            },
            'moderate-risk': {
                'bg': (1.0, 0.95, 0.88),
                'text': (0.94, 0.42, 0.0)
            },
            'low-risk': {
                'bg': (1.0, 0.98, 0.77),
                'text': (0.98, 0.66, 0.15)
            },
            'original': {
                'bg': (0.91, 0.96, 0.91),
                'text': (0.18, 0.49, 0.2)
            }
        }
        self.fallback_fonts = ["helv", "times", "cour"]

    def process_pdf(self, pdf_file) -> Tuple[str, Dict[str, Any]]:
        try:
            temp_input = tempfile.NamedTemporaryFile(delete=False, suffix='.pdf')
            pdf_file.save(temp_input.name)
            paragraphs, paragraph_block_mapping = self._extract_paragraphs(temp_input.name)
            report = self.plagiarism_service.check_plagiarism_content(paragraphs)
            output_path = self._highlight_pdf(temp_input.name, report['data']['paragraphs'], paragraph_block_mapping)
            return output_path, report
        except Exception as e:
            logging.exception("PDF plagiarism check failed")
            raise Exception(f"PDF plagiarism check failed: {str(e)}")
        finally:
            if 'temp_input' in locals() and os.path.exists(temp_input.name):
                os.unlink(temp_input.name)

    def _extract_paragraphs(self, pdf_path: str) -> Tuple[Dict[str, str], Dict[str, List[str]]]:
        paragraphs = {}
        paragraph_block_mapping = {}
        doc = fitz.open(pdf_path)
        current_paragraph = ""
        current_keys = []
        paragraph_index = 0

        for page_num in range(len(doc)):
            page = doc[page_num]
            blocks = page.get_text("dict")["blocks"]
            for block_num, block in enumerate(blocks):
                if "lines" not in block:
                    continue
                text = ""
                for line in block["lines"]:
                    for span in line["spans"]:
                        text += span["text"]
                    text += " "
                cleaned_text = text.strip()
                if cleaned_text:
                    current_paragraph += " " + cleaned_text
                    current_keys.append(f"page_{page_num}_block_{block_num}")
                    if len(current_paragraph) >= 2000:
                        key = f"paragraph_{paragraph_index}"
                        paragraphs[key] = current_paragraph.strip()
                        paragraph_block_mapping[key] = current_keys.copy()
                        paragraph_index += 1
                        current_paragraph = ""
                        current_keys = []

        if current_paragraph.strip():
            key = f"paragraph_{paragraph_index}"
            paragraphs[key] = current_paragraph.strip()
            paragraph_block_mapping[key] = current_keys.copy()

        doc.close()
        return paragraphs, paragraph_block_mapping

    def _get_highlight_color(self, similarity: float) -> Dict[str, Tuple[float, float, float]]:
        if similarity > 85:
            return self.colors['high-risk']
        elif similarity > 65:
            return self.colors['moderate-risk']
        elif similarity > 30:
            return self.colors['low-risk']
        return self.colors['original']

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

    def _highlight_pdf(self, input_path: str, plagiarism_results: List[Dict[str, Any]], paragraph_block_mapping: Dict[str, List[str]]) -> str:
        temp_output = tempfile.NamedTemporaryFile(delete=False, suffix='_highlighted.pdf')
        result_map = {result['id']: result for result in plagiarism_results}
        doc = fitz.open(input_path)

        for paragraph_id, block_keys in paragraph_block_mapping.items():
            if paragraph_id not in result_map:
                continue
            similarity = result_map[paragraph_id]['similarity_percentage']
            colors = self._get_highlight_color(similarity)

            for key in block_keys:
                parts = key.split('_')
                page_num = int(parts[1])
                block_num = int(parts[3])
                page = doc[page_num]
                block = page.get_text("dict")["blocks"][block_num]
                block_rect = None
                for line in block["lines"]:
                    for span in line["spans"]:
                        rect = fitz.Rect(span["bbox"])
                        block_rect = rect if block_rect is None else block_rect | rect
                if block_rect:
                    block_rect += (-3, -3, 3, 3)
                    highlight = page.add_rect_annot(block_rect)
                    highlight.set_colors(fill=colors['bg'], stroke=colors['text'])
                    highlight.set_opacity(0.5)
                    highlight.set_border(width=1.0)
                    highlight.update()

        doc.save(temp_output.name, garbage=4, deflate=True, clean=True)
        doc.close()
        return temp_output.name
