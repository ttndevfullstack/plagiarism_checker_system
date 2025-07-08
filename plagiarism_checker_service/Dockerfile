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

# Copy entrypoint script
COPY ./z-docker-resources/flask/setup-entrypoint.sh /setup-entrypoint.sh
RUN chmod +x /setup-entrypoint.sh

ENTRYPOINT ["/setup-entrypoint.sh"]