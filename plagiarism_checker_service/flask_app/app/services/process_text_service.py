import os
import re
import nltk
from typing import List
from nltk.corpus import stopwords
from nltk.stem import WordNetLemmatizer
from nltk.tokenize import sent_tokenize, word_tokenize

class ProcessTextService:
    _nltk_initialized = False

    def __init__(self):
        # Initialize NLTK only once for all instances
        ProcessTextService.initialize_nltk()
        self.stop_words = set(stopwords.words('english'))
        self.lemmatizer = WordNetLemmatizer()

        # Chunking config
        self.min_paragraph_length = 50  # Minimum characters to consider as paragraph
        self.max_sentence_length = 500  # Maximum characters per sentence chunk
        self.min_sentence_length = 20   # Minimum characters per sentence

    @classmethod
    def _verify_nltk_resource(cls, resource_name, resource_path):
        """Verify if NLTK resource exists"""
        try:
            if resource_name == 'wordnet':
                from nltk.corpus import wordnet
                wordnet.ensure_loaded()
                return True
            elif resource_name == 'punkt':
                return all(
                    nltk.data.find(f'tokenizers/{res}') is not None 
                    for res in ['punkt', 'punkt_tab']
                )
            return nltk.data.find(resource_path) is not None
        except (LookupError, ImportError):
            return False

    @classmethod
    def initialize_nltk(cls):
        """Initialize NLTK resources only once"""
        if cls._nltk_initialized:
            return

        try:
            nltk_data_dir = os.getenv('NLTK_DATA', 
                os.path.join(os.path.dirname(__file__), '..', '..', 'nltk_data'))
            os.makedirs(nltk_data_dir, exist_ok=True)
            nltk.data.path.insert(0, nltk_data_dir)

            resources = {
                'punkt': 'tokenizers/punkt',
                'punkt_tab': 'tokenizers/punkt_tab',
                'stopwords': 'corpora/stopwords',
                'wordnet': 'corpora/wordnet',
                'omw-1.4': 'corpora/omw-1.4'
            }

            if not cls._verify_nltk_resource('punkt', 'tokenizers/punkt'):
                print("Downloading punkt tokenizer...")
                nltk.download('punkt', download_dir=nltk_data_dir, quiet=True)

            for resource_name, resource_path in resources.items():
                if resource_name != 'punkt' and not cls._verify_nltk_resource(resource_name, resource_path):
                    print(f"Downloading {resource_name}...")
                    success = nltk.download(resource_name, download_dir=nltk_data_dir, quiet=True)
                    if not success:
                        raise LookupError(f"Failed to download {resource_name}")

            cls._nltk_initialized = True
            print("NLTK initialization completed successfully")

        except Exception as e:
            print(f"Error initializing NLTK: {str(e)}")
            raise

    def preprocess_text(self, text: str) -> str:
        """Enhanced text preprocessing"""
        text = text.lower()
        text = re.sub(r'[^\w\s\.\n]', ' ', text)
        text = re.sub(r'\d+', '', text)

        sentences = sent_tokenize(text)
        processed_sentences = []

        for sent in sentences:
            tokens = word_tokenize(sent)
            tokens = [token for token in tokens 
                      if token not in self.stop_words and len(token) > 1]
            tokens = [self.lemmatizer.lemmatize(token) for token in tokens]
            processed_sentences.append(' '.join(tokens))

        return ' '.join(processed_sentences)

    def split_into_chunks(self, text: str) -> List[str]:
        """Split text into meaningful chunks at both paragraph and sentence level."""
        paragraphs = self._split_paragraphs(text)
        chunks = []
        for para in paragraphs:
            if len(para) <= self.max_sentence_length:
                chunks.append(para)
            else:
                sentence_chunks = self._split_sentences(para)
                chunks.extend(sentence_chunks)
        return chunks

    def _split_paragraphs(self, text: str) -> List[str]:
        """Split text into meaningful paragraphs"""
        paragraphs = re.split(r'\n\s*\n|\r\n\s*\r\n', text.strip())
        return [p.strip() for p in paragraphs if p.strip() and len(p) >= self.min_paragraph_length]

    def _split_sentences(self, paragraph: str) -> List[str]:
        """Split paragraph into optimal sentence chunks"""
        sentences = sent_tokenize(paragraph)
        chunks = []
        current_chunk = []
        current_length = 0

        for sent in sentences:
            sent = sent.strip()
            if not sent:
                continue

            sent_length = len(sent)

            if sent_length > self.max_sentence_length:
                if current_chunk:
                    chunks.append(' '.join(current_chunk))
                    current_chunk = []
                    current_length = 0
                chunks.extend(self._split_long_sentence(sent))
                continue

            if current_length + sent_length <= self.max_sentence_length:
                current_chunk.append(sent)
                current_length += sent_length
            else:
                if current_chunk:
                    chunks.append(' '.join(current_chunk))
                current_chunk = [sent]
                current_length = sent_length

        if current_chunk:
            chunks.append(' '.join(current_chunk))

        return chunks

    def _split_long_sentence(self, sentence: str) -> List[str]:
        """Split very long sentences into smaller chunks"""
        words = sentence.split()
        chunks = []
        current_chunk = []
        current_length = 0

        for word in words:
            word_length = len(word)
            if current_length + word_length > self.max_sentence_length and current_chunk:
                chunks.append(' '.join(current_chunk))
                current_chunk = []
                current_length = 0
            current_chunk.append(word)
            current_length += word_length + 1

        if current_chunk:
            chunks.append(' '.join(current_chunk))

        return chunks
