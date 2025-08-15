import os
import random
import string
from flask import Flask, jsonify, send_from_directory, request
import imap_client

# The static_folder is configured to point to the 'static' directory within the 'frontend' folder.
# The template_folder is configured to point to the 'frontend' folder for index.html.
app = Flask(__name__, static_folder='../frontend/static', template_folder='../frontend')

# A simple in-memory store for generated addresses to prevent duplicates if needed.
GENERATED_ADDRESSES = set()

def generate_unique_address():
    """Generates a unique random email address."""
    domain = os.getenv('DOMAIN', 'yourdomain.com')
    while True:
        name = ''.join(random.choices(string.ascii_lowercase + string.digits, k=12))
        address = f"{name}@{domain}"
        if address not in GENERATED_ADDRESSES:
            GENERATED_ADDRESSES.add(address)
            return address

@app.route('/')
def index():
    # Serve the main index.html file.
    return send_from_directory(app.template_folder, 'index.html')

@app.route('/api/generate-address')
def new_address():
    """Endpoint to generate a new temporary email address."""
    address = generate_unique_address()
    return jsonify({'address': address})

@app.route('/api/emails/<address>')
def get_emails(address):
    """Endpoint to fetch emails for a given address."""
    domain = os.getenv('DOMAIN', 'yourdomain.com')
    if not "@" in address or not address.endswith(domain):
        return jsonify({"error": "Invalid address format"}), 400

    emails = imap_client.fetch_emails_for_address(address)
    return jsonify(emails)

@app.route('/api/cleanup', methods=['POST'])
def cleanup_emails():
    """
    Endpoint to trigger the cleanup of old emails.
    This should be protected by a secret key passed in the headers.
    """
    secret_key = os.getenv('CLEANUP_SECRET')
    if not secret_key or request.headers.get('X-Cleanup-Secret') != secret_key:
        return jsonify({"error": "Unauthorized"}), 401

    try:
        imap_client.cleanup_old_emails()
        return jsonify({"status": "success", "message": "Cleanup process completed."})
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500

if __name__ == '__main__':
    # For local development. On cPanel, a production WSGI server like Gunicorn will run the app.
    app.run(debug=True, port=5000)
