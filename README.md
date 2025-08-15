# Temp Mail - Temporary Email Address Generator

This project is a simple, self-hosted temporary email website. It uses Python (Flask) for the backend and vanilla HTML, CSS, and JavaScript for the frontend. It's designed to be deployed on a cPanel hosting environment that supports Python applications.

The system works by leveraging cPanel's "Default Address" or "Catch-All" feature. You configure all emails for a domain that are sent to non-existent addresses to be forwarded to a single, real email account. This application then connects to that account via IMAP, scans for emails sent to the temporary addresses it generates, and displays them to the user.

## Features

-   Generate random, single-use temporary email addresses.
-   Automatically checks for and displays new emails.
-   Clean, modern, and responsive user interface.
-   Automatic cleanup of emails older than 7 days.
-   Securely handles email credentials using environment variables.

## How to Deploy on cPanel

Follow these steps carefully to get your temporary mail service running.

### Step 1: Configure the Default Address in cPanel

1.  Log in to your cPanel.
2.  Go to the "Email" section and click on **Default Address**.
3.  Select the domain you want to use for the temporary emails.
4.  Choose the option **Forward to Email Address** and enter the full email address of the account you want to use as the catch-all (e.g., `catchall@yourdomain.com`). This must be a real email account that you have created in cPanel.
5.  Click **Change**. All emails to non-existent addresses on your domain will now be routed to this inbox.

### Step 2: Set Up the Python Application

1.  In cPanel, go to the "Software" section and click on **Setup Python App**.
2.  Click **Create Application**.
3.  Set the **Python version** to 3.6 or higher.
4.  Set the **Application root** to the directory where you have uploaded the project files (e.g., `temp-mail`).
5.  Set the **Application URL** to the subdomain or domain path where you want to access the application.
6.  The **Application startup file** should be `backend/app.py`.
7.  The **Application Entry point** should be `app`.
8.  Click **Create**.

### Step 3: Install Dependencies

1.  After the application is created, scroll down to the "Configuration files" section on the same page.
2.  You will see a text box for `requirements.txt`. Enter `requirements.txt` and click **Add**.
3.  Click **Run Pip Install** to install the libraries listed in the `requirements.txt` file (Flask and python-dotenv).

### Step 4: Set Environment Variables

This is the most important step for configuring the application.

1.  On the same Python application setup page, scroll down to the **Environment Variables** section.
2.  Click **Add Variable** and add the following variables one by one:

    | Key             | Value                               |
    | --------------- | ----------------------------------- |
    | `IMAP_HOST`     | `your_imap_server.com`              |
    | `IMAP_USER`     | `catchall@yourdomain.com`           |
    | `IMAP_PASS`     | `your_email_password`               |
    | `IMAP_PORT`     | `993`                               |
    | `DOMAIN`        | `yourdomain.com`                    |
    | `CLEANUP_SECRET`| `generate_a_long_random_string`     |

    **Note:** Get the IMAP server details from your hosting provider or cPanel's "Email Accounts" -> "Connect Devices" section. The `DOMAIN` should be the same one you configured for the default address.

3.  After adding the variables, **restart** the application by clicking the "Restart" button at the top right.

### Step 5: Set Up the Cron Job for Email Cleanup

To automatically delete emails older than 7 days, you need to set up a cron job.

1.  In cPanel, go to the "Advanced" section and click on **Cron Jobs**.
2.  Under "Add New Cron Job", select a schedule. Running it once a day is usually sufficient. For example, to run it at midnight every day, select:
    *   **Common Settings:** Once Per Day (`0 0 * * *`)
3.  In the **Command** field, you need to enter a `curl` command to call the cleanup endpoint. The command should look like this (replace the URL and secret key):

    ```bash
    curl -X POST -H "X-Cleanup-Secret: your_secret_key_from_env" https://your-app-url.com/api/cleanup >/dev/null 2>&1
    ```
    *   Replace `your_secret_key_from_env` with the same `CLEANUP_SECRET` you set in the environment variables.
    *   Replace `https://your-app-url.com` with the actual URL of your application.
    *   `>/dev/null 2>&1` prevents the cron job from sending you an email every time it runs.

4.  Click **Add New Cron Job**.

Your temporary mail service should now be fully operational!
