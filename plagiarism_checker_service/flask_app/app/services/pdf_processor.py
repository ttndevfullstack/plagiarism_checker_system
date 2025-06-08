import os
import fitz
import tempfile
import subprocess

from typing import Dict, Any, List, Tuple
from flask_app.app.services.file_handler import FileHandler
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.services.plagiarism_checker_service import PlagiarismCheckerService

class PDFProcessor:
    def __init__(self, embedding_model: str):
        self.file_handler = FileHandler()
        self.text_service = ProcessTextService()
        self.plagiarism_service = PlagiarismCheckerService(embedding_model)
        self.min_sentence_length = 50

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
            report = self.plagiarism_service.check_plagiarism(sentences, document_word_count)

            # ✅ 3. Output highlighted PDF file
            output_path = self._highlight_pdf(file_path, report['data']['paragraphs'], sentence_data)
            
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
                        block_sentences = self.text_service.chunk_text_into_sentences(text)
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
                            
                            if len(current_text.strip()) >= self.min_sentence_length:
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

    def _highlight_pdf(self, input_path: str, plagiarism_results: List[Dict[str, Any]], sentence_data: Dict[str, Dict]) -> str:
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
            return None

        processed_chunks = set()
        
        # Iterate through plagiarism results and highlight matching chunks
        for key, result in result_map.items():
            if key not in sentence_data:
                continue

            chunk_text = sentence_data[key]['combined_text']
            if chunk_text in processed_chunks:
                continue

            similarity = result['similarity_percentage']
            highlight_color = get_highlight_color(similarity)
            
            if highlight_color is not None:
                # Search in all pages
                for page_num in range(len(doc)):
                    page = doc[page_num]
                    # Highlight the exact matching text
                    text_instances = page.search_for(chunk_text)
                    
                    for inst in text_instances:
                        highlight = page.add_highlight_annot(inst)
                        highlight.set_colors(stroke=highlight_color)
                        highlight.set_opacity(0.5)
                        highlight.update()
                
                processed_chunks.add(chunk_text)

        # Save and optimize the PDF
        doc.save(temp_output.name, garbage=4, deflate=True, clean=True)
        doc.close()
        return temp_output.name
