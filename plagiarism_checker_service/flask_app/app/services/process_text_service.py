import os
import re
import nltk
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
        # Lower case
        text = text.lower()
        # Remove unwanted characters
        text = re.sub(r'[^\w\s\.\n]', ' ', text)
        # Remove numbers
        text = re.sub(r'\d+', '', text)
        # Sentence tokenization
        sentences = sent_tokenize(text)

        processed_sentences = []
        for sent in sentences:
            # Word tokenization
            tokens = word_tokenize(sent)
            tokens = [token for token in tokens 
                      if token not in self.stop_words and len(token) > 1]
            # Lemmatization - Reduces words(e.g., "running" → "run")
            tokens = [self.lemmatizer.lemmatize(token) for token in tokens]
            processed_sentences.append(' '.join(tokens))

        return ' '.join(processed_sentences)
    
    def chunk_text_into_paragraphs(self, text: str) -> list:
        """Split raw text into paragraphs using double line breaks or block hints"""
        paragraphs = [p.strip() for p in re.split(r'\n\s*\n+', text) if len(p.strip()) >= self.min_paragraph_length]
        return paragraphs