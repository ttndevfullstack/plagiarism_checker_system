# Plagiarism Checker System

## Overview
This project is a plagiarism checker system built using Flask. It provides services to process and compare text for plagiarism detection.

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/plagiarism_checker_system.git
   cd plagiarism_checker_system
   ```

2. Set up a virtual environment:
   ```bash
   python3 -m venv venv
   source venv/bin/activate
   ```

3. Install dependencies:
   ```bash
   pip install -r requirements.txt
   ```

## Running the Application
1. Navigate to the Flask app directory:
   ```bash
   cd flask_app
   ```

2. Run the Flask application:
   ```bash
   flask run
   ```

3. Access the application at `http://127.0.0.1:5000`.

## NLTK Data Setup
The project requires NLTK data for text processing. The data will be automatically downloaded on first run to:
- Default: `./flask_app/nltk_data/`
- Custom: Set `NLTK_DATA` environment variable to specify a different path

Required NLTK packages:
- punkt
- stopwords
- wordnet
- omw-1.4

## Testing
Run the test suite using:
```bash
pytest
```

## License
This project is licensed under the MIT License. See the LICENSE file for details.