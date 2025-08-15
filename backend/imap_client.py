import os
import imaplib
import email
from email.header import decode_header
import datetime
from dotenv import load_dotenv

# Load environment variables from a .env file
load_dotenv()

# Fetch IMAP configuration from environment variables
IMAP_HOST = os.getenv('IMAP_HOST')
IMAP_USER = os.getenv('IMAP_USER')
IMAP_PASS = os.getenv('IMAP_PASS')
IMAP_PORT = int(os.getenv('IMAP_PORT', 993)) # Default to 993 for IMAP over SSL

def connect():
    """
    Connects to the IMAP server using credentials from environment variables.
    Returns an IMAP4_SSL connection object.
    """
    try:
        mail = imaplib.IMAP4_SSL(IMAP_HOST, IMAP_PORT)
        mail.login(IMAP_USER, IMAP_PASS)
        return mail
    except Exception as e:
        print(f"Error connecting to IMAP server: {e}")
        return None

def fetch_emails_for_address(address):
    """
    Fetches emails for a specific address from the catch-all inbox.
    """
    mail = connect()
    if not mail:
        return []

    try:
        mail.select('inbox')

        # Search for emails sent to the specific temporary address
        status, messages = mail.search(None, '(TO "{}")'.format(address))
        if status != 'OK':
            return []

        emails = []
        for num in messages[0].split():
            status, data = mail.fetch(num, '(RFC822)')
            if status != 'OK':
                continue

            msg = email.message_from_bytes(data[0][1])

            # Decode subject
            subject, encoding = decode_header(msg['subject'])[0]
            if isinstance(subject, bytes):
                subject = subject.decode(encoding if encoding else 'utf-8')

            # Decode sender
            from_ = msg.get('From')

            # Get email body
            body = ""
            if msg.is_multipart():
                for part in msg.walk():
                    content_type = part.get_content_type()
                    content_disposition = str(part.get("Content-Disposition"))

                    if content_type == 'text/plain' and 'attachment' not in content_disposition:
                        body = part.get_payload(decode=True).decode()
                        break
            else:
                body = msg.get_payload(decode=True).decode()

            emails.append({'from': from_, 'subject': subject, 'body': body})

        return emails
    finally:
        mail.logout()


def cleanup_old_emails(days=7):
    """
    Deletes emails older than a specified number of days from the inbox.
    """
    mail = connect()
    if not mail:
        return

    try:
        mail.select('inbox')

        # Calculate the date to search for emails before
        date = (datetime.date.today() - datetime.timedelta(days)).strftime("%d-%b-%Y")

        # Search for emails older than the specified date
        status, messages = mail.search(None, '(BEFORE "{}")'.format(date))
        if status != 'OK':
            return

        for num in messages[0].split():
            mail.store(num, '+FLAGS', '\\Deleted')

        mail.expunge()
    finally:
        mail.logout()
