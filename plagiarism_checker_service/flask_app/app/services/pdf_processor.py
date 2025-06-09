import os
import fitz
import tempfile
import subprocess
from typing import Dict, Any, Tuple

from flask_app.config import Config
from flask_app.app.services.file_handler import FileHandler
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.services.plagiarism_checker_service import PlagiarismCheckerService

class PDFProcessor:
    def __init__(self, embedding_model: str):
        self.file_handler = FileHandler()
        self.text_service = ProcessTextService()
        self.plagiarism_service = PlagiarismCheckerService(embedding_model)
        self.min_sentence_word = 3

    def convert_docx_to_pdf(self, input_path, output_dir=None):
        if output_dir is None:
            output_dir = os.path.dirname(input_path)
        # LibreOffice needs absolute paths
        cmd = [
            'libreoffice',
            '--headless',
            '--convert-to', 'pdf',
            '--outdir', output_dir,
            input_path
        ]
        subprocess.run(cmd, check=True)
        # Output PDF path
        pdf_path = os.path.join(output_dir, os.path.splitext(os.path.basename(input_path))[0] + '.pdf')
        return pdf_path

    def process_pdf(self, file) -> Tuple[str, Dict[str, Any]]:
        """Process PDF file and return the plagiarism report and highlighted PDF path"""
        try:
            # ✅ 1. Save file
            file_path = self.file_handler.save_file(file)
            old_path = None
            if file_path.lower().endswith('.docx'):
                old_path = file_path
                file_path = self.convert_docx_to_pdf(file_path)
            
            # ✅ 2. Chunk text
            sentence_data, document_word_count = self._extract_sentences(file_path)
            sentences = {k: v['combined_text'] for k, v in sentence_data.items()}
            
            # ✅ 3. Check plagiarism
            report, source_color_index_map = self.plagiarism_service.check_plagiarism(sentences, document_word_count)

            # ✅ 3. Output highlighted PDF file
            output_path = self._highlight_pdf(file_path, report['data']['paragraphs'], sentence_data, source_color_index_map)
            
            return output_path, report
        except Exception as e:
            raise Exception(f"PDF plagiarism check failed: {str(e)}")
        finally:
            if old_path:
                self.file_handler.remove_file(old_path)
            self.file_handler.remove_file(file_path)

    def _extract_sentences(self, pdf_path: str) -> Tuple[Dict[str, Dict], int]:
        """Extract text from PDF by sentences with unique keys and return total word count"""
        sentences = {}
        document_word_count = 0
        doc = fitz.open(pdf_path)
        
        for page_num in range(len(doc)):
            page = doc[page_num]
            blocks = page.get_text("dict")["blocks"]
            
            current_text = ""
            current_key = ""
            current_sentences = []
            
            for block_num, block in enumerate(blocks):
                if "lines" in block:
                    text = ""
                    for line in block["lines"]:
                        for span in line["spans"]:
                            text += span["text"]
                        text += " "
                    
                    text = text.strip()
                    if text:
                        block_sentences = self.text_service.chunk_text(text)
                        for sent_num, sentence in enumerate(block_sentences):
                            sentence = sentence.strip()
                            if not sentence:
                                continue
                                
                            # Count words in the sentence
                            document_word_count += len(sentence.split())
                                
                            if not current_text:
                                current_text = sentence
                                current_key = f"page_{page_num}_block_{block_num}_sent_{sent_num}"
                                current_sentences = [sentence]
                            else:
                                current_text += " " + sentence
                                current_sentences.append(sentence)
                            
                            if len(current_text.strip()) >= getattr(Config, "MIN_CHUNKED_TEXT_LENGTH", 15):
                                sentences[current_key] = {
                                    'combined_text': current_text.strip(),
                                    # 'original_sentences': current_sentences
                                }
                                current_text = ""
                                current_key = ""
                                current_sentences = []
            
            # Handle any remaining text at the end of each page
            if current_text and current_key:
                sentences[current_key] = {
                    'combined_text': current_text.strip(),
                    # 'original_sentences': current_sentences
                }
        
        doc.close()
        return sentences, document_word_count

    def _highlight_pdf(self, input_path: str, paragraphs: list, sentence_data: dict, source_color_index_map: dict) -> str:
        temp_output = tempfile.NamedTemporaryFile(delete=False, suffix='_highlighted.pdf')
        doc = fitz.open(input_path)
        from flask_app.config import Config

        # Use the same color assignment as report
        already_highlighted = set()
        for para in paragraphs:
            para_id = para["id"]
            # Pick the first source (or your preferred logic)
            if not para.get("sources"):
                continue
            best_source = para["sources"][0]
            key = f"{best_source['document_id']}::{best_source['title']}"
            color_index = source_color_index_map.get(key, -1)
            if color_index == -1:
                continue  # skip if not in top sources

            highlight_color = Config.HIGHLIGHT_COLORS[color_index]
            chunk_text = sentence_data[para_id]['combined_text']
            if (chunk_text, key) in already_highlighted:
                continue
            for page_num in range(len(doc)):
                page = doc[page_num]
                instances = page.search_for(chunk_text)
                for inst in instances:
                    highlight = page.add_highlight_annot(inst)
                    highlight.set_colors(stroke=highlight_color)
                    highlight.set_opacity(0.5)
                    highlight.update()
            already_highlighted.add((chunk_text, key))

        doc.save(temp_output.name, garbage=4, deflate=True, clean=True)
        doc.close()
        return temp_output.name
