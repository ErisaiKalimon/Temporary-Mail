# 🚀 Quick Start Guide - Temp Mail Service

Get up and running in 5 minutes!

## For Complete Beginners

### Step 1: Install XAMPP (Easiest Method)

#### Windows:
1. Download XAMPP from: https://www.apachefriends.org/download.html
2. Run the installer (accept all defaults)
3. Start XAMPP Control Panel
4. Click "Start" button next to "Apache"

#### Mac:
1. Download XAMPP for Mac from the same link
2. Mount the DMG and drag XAMPP to Applications
3. Open Terminal and run: `sudo /Applications/XAMPP/xamppfiles/xampp start`

#### Linux:
```bash
# Download and install
wget https://www.apachefriends.org/xampp-files/8.2.0/xampp-linux-x64-8.2.0-0-installer.run
chmod +x xampp-linux-*-installer.run
sudo ./xampp-linux-*-installer.run

# Start Apache
sudo /opt/lampp/lampp start
```

### Step 2: Install the Project

#### Option A: Download ZIP (No Git Required)
1. Download this project as ZIP
2. Extract the ZIP file
3. Copy the `temp-mail` folder to:
   - **Windows**: `C:\xampp\htdocs\temp-mail`
   - **Mac**: `/Applications/XAMPP/htdocs/temp-mail`
   - **Linux**: `/opt/lampp/htdocs/temp-mail`

#### Option B: Using Git (Recommended)
```bash
# Windows (Git Bash or Command Prompt)
cd C:\xampp\htdocs
git clone <repository-url> temp-mail

# Mac/Linux
cd /Applications/XAMPP/htdocs  # Mac
# or
cd /opt/lampp/htdocs           # Linux
git clone <repository-url> temp-mail
```

### Step 3: Access the Application

1. Open your web browser (Chrome, Firefox, Safari, Edge)
2. Navigate to: **http://localhost/temp-mail**
3. That's it! The app should load automatically.

## Verification Checklist

✅ **Check if Apache is running:**
- XAMPP Control Panel shows "Apache" with green background
- Or visit: http://localhost (should show XAMPP dashboard)

✅ **Check if files are in correct location:**
```bash
# Windows
dir C:\xampp\htdocs\temp-mail

# Mac/Linux
ls /Applications/XAMPP/htdocs/temp-mail
# or
ls /opt/lampp/htdocs/temp-mail
```

You should see files: index.php, api-handler.php, config.php, assets/

✅ **Check PHP is working:**
- Visit: http://localhost/temp-mail/config.php
- You should see a blank page (not an error or download prompt)

## Common Issues & Solutions

### Issue: "This site can't be reached"
**Solution:** Apache is not running. Start it from XAMPP Control Panel.

### Issue: Browser downloads index.php instead of running it
**Solution:** Apache is not configured correctly. Restart Apache from XAMPP.

### Issue: 404 - Page Not Found
**Solution:** 
- Check the URL is correct: `http://localhost/temp-mail`
- Verify files are in htdocs folder
- Check folder name is exactly `temp-mail`

### Issue: Blank white page
**Solution:**
1. Enable error display temporarily
2. Edit `config.php` and change:
   ```php
   ini_set('display_errors', 1);  // Change 0 to 1
   ```
3. Refresh page to see actual error
4. Check `error.log` file in project folder

### Issue: cURL error
**Solution:**
1. Open `php.ini` (XAMPP Control Panel → Apache → Config → php.ini)
2. Find line: `;extension=curl`
3. Remove the semicolon: `extension=curl`
4. Restart Apache

## Testing the Application

1. **Email Generation:**
   - Page loads → Email address appears automatically
   - Click "Copy" → Should show "Email copied" message

2. **Inbox:**
   - Send a test email to your generated address (from Gmail, etc.)
   - Wait 5-10 seconds
   - Email should appear in inbox automatically

3. **Read Email:**
   - Click on any email in the list
   - Modal opens showing full email content
   - Click "Close" to return to inbox

4. **New Email:**
   - Click "New Email" button
   - Confirm the dialog
   - New email address is generated
   - Previous messages are cleared

## Alternative: PHP Built-in Server (For Testing)

If you don't want to install XAMPP:

```bash
# Navigate to project folder
cd temp-mail

# Start PHP server (requires PHP installed)
php -S localhost:8000

# Open browser to:
http://localhost:8000
```

**Note:** This requires PHP to be installed on your system.

### Installing PHP (if needed):

**Windows:**
- Download from: https://windows.php.net/download/
- Or use Chocolatey: `choco install php`

**Mac:**
- Use Homebrew: `brew install php`

**Linux:**
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php php-curl php-json

# CentOS/RHEL
sudo yum install php php-curl php-json
```

## Next Steps

Once everything is working:

1. **Read the full README.md** for detailed documentation
2. **Customize** the refresh interval, colors, or messages in `config.php`
3. **Deploy to production** (see README for deployment guide)
4. **Star the repo** if you find it useful! ⭐

## Need Help?

1. Check the **Troubleshooting** section in README.md
2. Review browser console for JavaScript errors (F12 → Console)
3. Check `error.log` file in project folder
4. Open an issue on GitHub with:
   - Your OS and PHP version
   - XAMPP version
   - Error message or screenshot
   - Browser and version

---

**Happy Testing! 📧**

*Remember: Temporary emails are for testing only. Don't use for sensitive information!*
