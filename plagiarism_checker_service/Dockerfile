# Use Python 3.12.3 as base
FROM python:3.12.3

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install dependencies
RUN pip install --no-cache-dir -r flask_app/requirements.txt

# Expose Flask's port
EXPOSE 5000

# Run Flask app
CMD ["flask", "run", "--host=0.0.0.0", "--port=5000"]
