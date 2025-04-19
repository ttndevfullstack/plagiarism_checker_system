import re
from difflib import SequenceMatcher

class ProcessTextService:
    def preprocess_text(self, text):
        """Remove special characters, extra spaces, and standardize text"""
        print("   ✅ Preprocess file")

        text = re.sub(r'[_-]{3,}', ' ', text) # Remove long sequences of underscores or dashes
        text = re.sub(r'\s+', ' ', text)  # Remove extra spaces
        text = re.sub(r'[^\w\s]', '', text)  # Remove special characters
        #remove stop works

        return text.lower().strip()

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