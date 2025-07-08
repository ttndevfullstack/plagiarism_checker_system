import os
import re
import nltk
import fitz

from nltk.corpus import stopwords
from flask_app.config import Config
from typing import List, Dict, Tuple
from nltk.stem import WordNetLemmatizer
from nltk.tokenize import sent_tokenize, word_tokenize


class ProcessTextService:
    def __init__(self):
        # Always load nltk_data from the shared path (no download/verify)
        nltk_data_dir = os.getenv('NLTK_DATA', os.path.join(os.path.dirname(__file__), '..', '..', 'nltk_data'))
        if nltk_data_dir not in nltk.data.path:
            nltk.data.path.insert(0, nltk_data_dir)
        self.stop_words = set(stopwords.words('english'))
        self.lemmatizer = WordNetLemmatizer()

    def clean_text(self, text: str, countable: bool = False) -> str:
        # Lower case
        text = text.lower()
        # Remove specific punctuations . , ? ! …
        text = re.sub(r'[.,?!…]', ' ', text)
        # Remove other unwanted characters except word and whitespace
        text = re.sub(r'[^\w\s]', ' ', text)
        # Remove numbers
        text = re.sub(r'\d+', '', text)
        # Remove newlines by replacing with space
        text = text.replace('\n', ' ')
        # Remove extra whitespaces
        text = re.sub(r'\s+', ' ', text).strip()

        if countable:
            return len(text.split())
        return text

    def preprocess_text(self, text: str) -> str:
        """Enhanced text preprocessing"""
        clean_text = self.clean_text(text)
        sentences = sent_tokenize(clean_text)

        processed_sentences = []
        for sent in sentences:
            # Word tokenization
            tokens = word_tokenize(sent)
            tokens = [token for token in tokens 
                      if token not in self.stop_words and len(token) > 1]
            # Lemmatization - Reduces words(e.g., "running" → "run")
            tokens = [self.lemmatizer.lemmatize(token) for token in tokens]
            processed_sentences.append(' '.join(tokens))

        processed_text = ' '.join(processed_sentences)
        return clean_text, processed_text
    
    def chunk_text_into_paragraphs(self, text: str) -> List[str]:
        """Split raw text into paragraphs using double line breaks or block hints"""
        paragraphs = [p.strip() for p in re.split(r'\n\s*\n+', text) if len(p.strip()) >= getattr(Config, "MIN_PARAGRAPH_LENGTH", 50)]
        return paragraphs
    
    def chunk_text_into_sentences(self, text: str) -> List[str]:
        """Split text into sentences using regex"""
        # Handle common abbreviations and special cases
        text = re.sub(r'([A-Z]\.)(?=[A-Z]\.)', r'\1|', text)  # Handle initials
        text = re.sub(r'(Mr\.|Mrs\.|Dr\.|Prof\.|Sr\.|Jr\.|vs\.|etc\.)', r'\1|', text)
        
        # Split into sentences
        sentences = re.split(r'(?<=[.!?])\s+(?=[A-Z])', text)
        
        # Remove the temporary marks and clean sentences
        sentences = [s.replace('|', '.').strip() for s in sentences if s.strip()]
        return sentences
    
    def chunk_text(self, raw_text: str, chunk_type: str = 'sentences') -> List[str]:
        if chunk_type == 'sentences':
            return self.chunk_text_into_sentences(raw_text)
        else:
            return self.chunk_text_into_paragraphs(raw_text)
        
    def extract_sentences(self, pdf_path: str) -> Tuple[Dict[str, Dict], int]:
        """
        Extract text from PDF by sentences with unique keys and return total word count.
        - Headings/titles remain their own sentence.
        - Body text is split into sentences by end punctuation (., !, ?, etc.).
        - Never split by comma.
        """
        print("   👉 Preprocessing text")

        def is_heading(line):
            if re.match(r'^\d+\.\s+', line):
                return True
            if len(line) < 40 and (line.isupper() or re.match(r'^[A-Za-z ]+$', line)):
                return True
            return False

        def split_sentences(text):
            # Split by end-of-sentence punctuation followed by a space or EOL
            # This pattern keeps the delimiter at the end of each sentence
            pattern = re.compile(r'([^.!?。．？！]*[.!?。．？！])', re.MULTILINE)
            sentences = [m.group(0).strip() for m in pattern.finditer(text) if m.group(0).strip()]
            # If there is any remaining text that doesn't end with punctuation
            remainder = pattern.sub('', text).strip()
            if remainder:
                sentences.append(remainder)
            return sentences

        sentences = {}
        document_word_count = 0
        doc = fitz.open(pdf_path)

        for page_num in range(len(doc)):
            page = doc[page_num]
            blocks = page.get_text("dict")["blocks"]

            buffer = ""
            sent_counter = 0

            for block_num, block in enumerate(blocks):
                if "lines" in block:
                    for line in block["lines"]:
                        text = "".join(span["text"] for span in line["spans"]).strip()
                        if not text:
                            continue

                        if is_heading(text):
                            # Flush buffer first
                            if buffer:
                                for s in split_sentences(buffer):
                                    if s:
                                        document_word_count += len(s.split())
                                        key = f"page_{page_num}_sent_{sent_counter}"
                                        sentences[key] = {
                                            'combined_text': s,
                                            'original_sentences': [s]
                                        }
                                        sent_counter += 1
                                buffer = ""
                            # Add heading
                            document_word_count += len(text.split())
                            key = f"page_{page_num}_sent_{sent_counter}"
                            sentences[key] = {
                                'combined_text': text,
                                'original_sentences': [text]
                            }
                            sent_counter += 1
                            continue

                        # Buffer non-heading lines
                        if buffer:
                            buffer += " " + text
                        else:
                            buffer = text

                        # Only flush buffer if line ends with sentence-ending punctuation
                        if re.search(r'[.!?。．？！]$', text):
                            for s in split_sentences(buffer):
                                if s:
                                    document_word_count += len(s.split())
                                    key = f"page_{page_num}_sent_{sent_counter}"
                                    sentences[key] = {
                                        'combined_text': s,
                                        'original_sentences': [s]
                                    }
                                    sent_counter += 1
                            buffer = ""

            # Flush any remaining buffer at the end of page
            if buffer:
                for s in split_sentences(buffer):
                    if s:
                        document_word_count += len(s.split())
                        key = f"page_{page_num}_sent_{sent_counter}"
                        sentences[key] = {
                            'combined_text': s,
                            'original_sentences': [s]
                        }
                        sent_counter += 1

        doc.close()
        return sentences, document_word_count
