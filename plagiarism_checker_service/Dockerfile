FROM python:3.10-slim

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    git \
    libgl1 \
    && rm -rf /var/lib/apt/lists/*

# Install Python dependencies
COPY plagiarism_checker_service/requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy app code
COPY ./plagiarism_checker_service /app

# Gunicorn startup for production
# CMD ["gunicorn", "-w", "2", "-b", "0.0.0.0:5000", "run:app", "--timeout", "1800"]

# Flask development server for debugging
CMD ["flask", "run", "--host=0.0.0.0", "--port=5000", "--reload"]