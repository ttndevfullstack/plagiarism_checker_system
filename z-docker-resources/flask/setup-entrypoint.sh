#!/bin/bash

set -e

if [ "$FLASK_ENV" = "development" ]; then
    echo "Running Flask in development mode..."
    exec flask run --host=0.0.0.0 --port=5000 --reload
else
    echo "Running Flask with Gunicorn in production mode..."
    exec gunicorn -w 2 -b 0.0.0.0:5000 run:app --timeout 1800
fi
