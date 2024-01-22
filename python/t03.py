import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

def send_email(subject, body, to_email, smtp_server, smtp_port, sender_email, sender_password):
    # Set up the MIME
    message = MIMEMultipart()
    message['From'] = sender_email
    message['To'] = to_email
    message['Subject'] = subject

    # Attach the body to the email
    message.attach(MIMEText(body, 'plain'))

    # Connect to the SMTP server with SSL
    with smtplib.SMTP_SSL(host=smtp_server, port=smtp_port, timeout=10) as server:
        # Login to your Naver account
        server.login(sender_email, sender_password)

        # Send the email
        server.sendmail(sender_email, to_email, message.as_string())

    print(f"Email sent successfully to {to_email}")

# Replace these variables with your own values
subject = "Hello from James!"
body = "This is a test email sent using Python."
to_email = "websiteman@naver.com"
smtp_server = "smtp.naver.com"
smtp_port = 465  # for SSL
sender_email = "gerukr@naver.com"
sender_password = "BJTJMZG7RNQL"

# Send the email with SSL
send_email(subject, body, to_email, smtp_server, smtp_port, sender_email, sender_password)

