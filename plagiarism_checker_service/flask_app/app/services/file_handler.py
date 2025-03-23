import os
import uuid
import PyPDF2

from docx import Document
from flask import current_app

class FileHandler:
    def save_file(self, file):
        file_path = os.path.join(
            current_app.config['FILE_STORAGE_DIR'],
            str(uuid.uuid4()) + os.path.splitext(file.filename)[1]
        )
        file.save(file_path)

        print("   ✅ Saved file in storage successfully")
        return file_path

    def extract_text_from_file(self, file_path):
        if file_path.endswith('.txt'):
            with open(file_path, 'r', encoding='utf-8') as file:
                print("   ✅ Extracted text in file successfully")
                return file.read()
        elif file_path.endswith('.docx'):
            doc = Document(file_path)
            print("   ✅ Extracted text in file successfully")
            return '\n'.join([para.text for para in doc.paragraphs])
        elif file_path.endswith('.pdf'):
            with open(file_path, 'rb') as file:
                reader = PyPDF2.PdfReader(file)
                print("   ✅ Extracted text in file successfully")
                return '\n'.join([page.extract_text() for page in reader.pages])
        else:
            raise ValueError("Unsupported file format")