import re
from difflib import SequenceMatcher
import nltk
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import WordNetLemmatizer
import os

class ProcessTextService:
    _nltk_initialized = False

    def __init__(self):
        # Initialize NLTK only once for all instances
        ProcessTextService.initialize_nltk()
        self.stop_words = set(stopwords.words('english'))
        self.lemmatizer = WordNetLemmatizer()
    
    @classmethod
    def _verify_nltk_resource(cls, resource_name, resource_path):
        """Verify if NLTK resource exists"""
        try:
            if resource_name == 'wordnet':
                from nltk.corpus import wordnet
                wordnet.ensure_loaded()
                return True
            elif resource_name == 'punkt':
                # Special verification for punkt and its related resources
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
            # Use environment variable or default path
            nltk_data_dir = os.getenv('NLTK_DATA', 
                os.path.join(os.path.dirname(__file__), '..', '..', 'nltk_data'))
            os.makedirs(nltk_data_dir, exist_ok=True)
            nltk.data.path.insert(0, nltk_data_dir)
            
            # Updated resource mapping with additional tokenizer resources
            resources = {
                'punkt': 'tokenizers/punkt',
                'punkt_tab': 'tokenizers/punkt_tab',
                'stopwords': 'corpora/stopwords',
                'wordnet': 'corpora/wordnet',
                'omw-1.4': 'corpora/omw-1.4'
            }
            
            # First download punkt as it's a prerequisite
            if not cls._verify_nltk_resource('punkt', 'tokenizers/punkt'):
                print("Downloading punkt tokenizer...")
                nltk.download('punkt', download_dir=nltk_data_dir, quiet=True)
            
            # Then download other resources
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

    def preprocess_text(self, text):
        """Remove special characters, extra spaces, and standardize text"""
        print("   ✅ Preprocess file")

        # Convert to lowercase
        text = text.lower()
        
        # Remove special characters and numbers
        text = re.sub(r'[^\w\s]', ' ', text)
        text = re.sub(r'\d+', '', text)
        
        # Tokenize
        tokens = word_tokenize(text)
        
        # Remove stop words and single characters
        tokens = [token for token in tokens if token not in self.stop_words and len(token) > 1]
        
        # Lemmatize
        tokens = [self.lemmatizer.lemmatize(token) for token in tokens]
        
        # Join tokens and standardize whitespace
        text = ' '.join(tokens)
        text = re.sub(r'\s+', ' ', text)
        
        return text.strip()

    def highlight_text(self, source, target, similarity):
        matcher = SequenceMatcher(None, source, target)
        highlighted = []
        for opcode in matcher.get_opcodes():
            tag, i1, i2, j1, j2 = opcode
            if tag == 'equal':
                highlighted.append(f'<span style="background-color: rgba(0, 255, 0, {similarity});">{source[i1:i2]}</span>')
            else:
                highlighted.append(source[i1:i2])
        return ''.join(highlighted)