# 📧 Temp Mail - Temporary Email Service

A complete, production-ready temporary email service built with PHP and vanilla JavaScript. Generate disposable email addresses, receive messages, and keep your real inbox clean!

![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![Status](https://img.shields.io/badge/status-production--ready-brightgreen)

## ✨ Features

- 🎯 **No Registration Required** - Start using immediately
- 🔄 **Auto-Refresh Inbox** - Messages appear automatically every 5 seconds
- 📋 **One-Click Copy** - Copy email address to clipboard instantly
- 📧 **Read Full Emails** - View HTML and text content safely
- 🔐 **No Server Email Config** - Uses third-party APIs (Mail.tm & 1secmail)
- 📱 **Fully Responsive** - Works on all devices
- 🎨 **Modern UI** - Built with Tailwind CSS
- ⚡ **Fast & Lightweight** - Pure PHP and vanilla JavaScript

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- cURL extension enabled
- Web server (Apache/Nginx)
- XAMPP, WAMP, Laragon, or similar local development environment

### Installation

#### Option 1: XAMPP (Windows/Mac/Linux)

1. **Download and Install XAMPP**
   - Download from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Install and start Apache from XAMPP Control Panel

2. **Clone/Download the Project**
   ```bash
   cd C:\xampp\htdocs  # Windows
   # or
   cd /Applications/XAMPP/htdocs  # Mac
   # or
   cd /opt/lampp/htdocs  # Linux
   
   git clone <repository-url> temp-mail
   ```

3. **Access the Application**
   - Open your browser and navigate to: `http://localhost/temp-mail`
   - That's it! No configuration needed.

#### Option 2: Laragon (Windows)

1. **Download and Install Laragon**
   - Download from [https://laragon.org/](https://laragon.org/)
   - Install and start All Services

2. **Clone/Download the Project**
   ```bash
   cd C:\laragon\www
   git clone <repository-url> temp-mail
   ```

3. **Access the Application**
   - Open: `http://temp-mail.test` (Laragon auto-creates domain)
   - Or: `http://localhost/temp-mail`

#### Option 3: Built-in PHP Server (Quick Test)

```bash
cd temp-mail
php -S localhost:8000

# Open browser to: http://localhost:8000
```

## 📁 Project Structure

```
temp-mail/
├── index.php              # Main application page
├── api-handler.php        # API integration & business logic
├── config.php             # Configuration settings
├── .gitignore            # Git ignore file
├── .env.example          # Environment variables example
├── README.md             # This file
├── LICENSE               # License file
└── assets/
    ├── css/
    │   └── style.css     # Custom styles
    └── js/
        └── app.js        # Main JavaScript application
```

## 🔧 Configuration

The application works out-of-the-box with default settings. If you need to customize:

1. Copy `.env.example` to `.env` (optional)
2. Edit `config.php` to modify:
   - API endpoints
   - Refresh intervals
   - Session settings
   - Error messages

### Configuration Options

```php
// Inbox refresh interval (milliseconds)
define('INBOX_REFRESH_INTERVAL', 5000); // 5 seconds

// Session lifetime (seconds)
ini_set('session.gc_maxlifetime', 3600); // 1 hour

// API endpoints (already configured)
define('MAIL_TM_API_BASE', 'https://api.mail.tm');
define('ONESECMAIL_API_BASE', 'https://www.1secmail.com/api/v1/');
```

## 🎯 How to Use

1. **Generate Email**
   - Visit the application
   - An email address is automatically generated
   - Copy it using the "Copy" button

2. **Receive Messages**
   - Use the email anywhere you need a temporary address
   - Messages appear automatically (auto-refresh every 5 seconds)
   - Click "Refresh" button for manual refresh

3. **Read Emails**
   - Click on any message to open and read it
   - View sender, subject, and full content
   - Close modal to return to inbox

4. **Generate New Email**
   - Click "New Email" button
   - Confirm to delete current email and generate a new one
   - Previous messages will be lost

## 🛠️ Technical Details

### Backend (PHP)

- **API Integration**: Uses Mail.tm (primary) and 1secmail (fallback)
- **Session Management**: PHP sessions to persist email data
- **cURL Requests**: All API calls use cURL with proper error handling
- **JSON Responses**: All endpoints return JSON data
- **PSR-12 Compliant**: Clean, readable code following PHP standards

### Frontend

- **Vanilla JavaScript**: No dependencies, no jQuery
- **ES6+ Features**: Classes, async/await, template literals
- **Fetch API**: Modern HTTP requests
- **Auto-Refresh**: setInterval for polling new messages
- **Responsive Design**: Mobile-first approach with Tailwind CSS

### API Endpoints

Internal API endpoints (via `api-handler.php`):

| Endpoint | Description |
|----------|-------------|
| `?action=generate` | Generate new temporary email |
| `?action=get_current` | Get current email from session |
| `?action=get_messages` | Fetch inbox messages |
| `?action=read_message&id=<id>` | Read specific message |
| `?action=delete_regenerate` | Delete and create new email |

### Security Features

- ✅ HTML sanitization for email content
- ✅ XSS protection with escaping
- ✅ CSRF protection via sessions
- ✅ Input validation
- ✅ Secure external link handling
- ✅ No SQL database (no SQL injection risk)

## 📋 Requirements

### Minimum Requirements

- PHP 7.4+
- cURL extension
- JSON extension
- Session support

### Recommended

- PHP 8.0+
- SSL certificate (for production)
- Apache mod_rewrite or Nginx rewrite rules

### Checking PHP Version

```bash
php -v
```

### Checking cURL Extension

```bash
php -m | grep curl
```

If cURL is not installed:

**Windows (XAMPP):**
- Edit `php.ini`
- Uncomment: `;extension=curl` → `extension=curl`
- Restart Apache

**Linux:**
```bash
sudo apt-get install php-curl
# or
sudo yum install php-curl
```

**Mac (Homebrew):**
```bash
brew install php
# cURL is included by default
```

## 🐛 Troubleshooting

### Issue: "Failed to generate email"

**Solution:**
- Check internet connection
- Verify cURL is enabled: `php -m | grep curl`
- Check error.log file for details
- Try using PHP built-in server: `php -S localhost:8000`

### Issue: "Session not persisting"

**Solution:**
- Ensure sessions directory is writable
- Check `session.save_path` in php.ini
- Verify cookies are enabled in browser

### Issue: "Page not found (404)"

**Solution:**
- Verify correct URL: `http://localhost/temp-mail` (include trailing path)
- Check Apache is running (XAMPP Control Panel)
- Ensure project is in correct directory (`htdocs` or `www`)

### Issue: "Messages not appearing"

**Solution:**
- Wait 5-10 seconds for auto-refresh
- Click "Refresh" button manually
- Check browser console for JavaScript errors
- Verify API is accessible (check browser network tab)

### Issue: "Cannot copy to clipboard"

**Solution:**
- Use HTTPS (clipboard API requires secure context)
- Grant clipboard permissions in browser
- Fallback: Manually select and copy the email

## 🔒 Security Notes

⚠️ **Important Security Considerations:**

1. **Temporary emails are PUBLIC** - Anyone can access them if they know the address
2. **No password protection** - Messages are not encrypted
3. **Emails expire** - Services may delete emails after some time
4. **Not for sensitive data** - Never use for banking, personal info, etc.
5. **Use cases**: Testing, one-time registrations, avoiding spam

## 📱 Browser Support

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Opera 76+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## 🎨 Customization

### Changing Refresh Interval

Edit `config.php`:

```php
define('INBOX_REFRESH_INTERVAL', 10000); // 10 seconds
```

### Changing Theme Colors

Edit `index.php` or add custom CSS in `assets/css/style.css`:

```css
/* Change primary color from indigo to purple */
.bg-indigo-600 { background-color: #9333ea !important; }
.text-indigo-600 { color: #9333ea !important; }
```

### Adding Custom Domain

Edit `config.php` for 1secmail:

```php
define('ONESECMAIL_DOMAINS', ['1secmail.com', 'custom.com']);
```

## 🚀 Deployment

### Production Deployment

1. **Use HTTPS** - Required for clipboard API
2. **Disable Debug Mode** - Set `display_errors = 0` in php.ini
3. **Set Proper Permissions** - Files: 644, Directories: 755
4. **Enable Error Logging** - Monitor error.log file
5. **Add Rate Limiting** - Prevent API abuse
6. **Use CDN** - For Tailwind CSS in production

### Recommended Production Settings

```php
// config.php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Enable production mode
define('APP_ENV', 'production');
```

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 🙏 Credits

- **APIs Used:**
  - [Mail.tm](https://mail.tm/) - Primary email service
  - [1secmail](https://www.1secmail.com/) - Fallback email service
- **UI Framework:** [Tailwind CSS](https://tailwindcss.com/)
- **Icons:** Unicode Emoji

## 📞 Support

If you encounter any issues or have questions:

1. Check the [Troubleshooting](#-troubleshooting) section
2. Review the error.log file
3. Open an issue on GitHub
4. Check browser console for JavaScript errors

## 🎯 Roadmap

- [ ] Add multiple email providers
- [ ] Implement email forwarding
- [ ] Add dark mode toggle
- [ ] Email attachments support
- [ ] Custom email prefix selection
- [ ] Browser extension
- [ ] Mobile app
- [ ] Docker containerization

## ⭐ Show Your Support

If you find this project useful, please give it a ⭐ on GitHub!

---

**Made with ❤️ using PHP and JavaScript**

*No server email configuration required • Fully anonymous • Production-ready*
