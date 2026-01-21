# 🚀 Quick Reference - Temp Mail Service

## 📦 Installation (30 seconds)

```bash
# 1. Place in web root
cp -r temp-mail /path/to/htdocs/

# 2. Start Apache (XAMPP)
# Click "Start" next to Apache in XAMPP Control Panel

# 3. Open browser
# Navigate to: http://localhost/temp-mail
```

**That's it!** No configuration needed.

## 🔗 Important URLs

| Purpose | URL |
|---------|-----|
| **Main App** | `http://localhost/temp-mail` |
| **Installation Test** | `http://localhost/temp-mail/test.php` |
| **Documentation** | `README.md` in project folder |
| **Quick Start** | `QUICKSTART.md` in project folder |

## 🎯 Core Features

| Feature | How to Use |
|---------|------------|
| **Generate Email** | Auto-generated on page load, or click "New Email" |
| **Copy Email** | Click "Copy" button |
| **Refresh Inbox** | Auto every 5s, or click "Refresh" |
| **Read Email** | Click on any message in inbox |
| **New Email** | Click "New Email" → Confirm |

## ⌨️ Keyboard Shortcuts

| Key | Action |
|-----|--------|
| `ESC` | Close email modal |
| `Ctrl+C` / `Cmd+C` | Copy selected text |
| `F5` | Refresh page |
| `F12` | Open developer tools |

## 🐛 Common Issues & Quick Fixes

### Issue: "Page not found" (404)
```bash
# Fix: Check URL is correct
http://localhost/temp-mail  ✅ Correct
http://localhost            ❌ Wrong
```

### Issue: "This site can't be reached"
```bash
# Fix: Start Apache
XAMPP Control Panel → Apache → Start
```

### Issue: "Failed to generate email"
```bash
# Fix 1: Check internet connection
ping google.com

# Fix 2: Enable cURL
# Edit php.ini, uncomment: extension=curl
# Restart Apache
```

### Issue: "Page downloads instead of running"
```bash
# Fix: PHP not configured
# Restart Apache from XAMPP
# Or use: php -S localhost:8000
```

### Issue: "Blank white screen"
```bash
# Fix: Check PHP errors
# Edit config.php:
ini_set('display_errors', 1);
# Refresh page to see error
```

## 📁 File Structure

```
temp-mail/
├── index.php          → Main page (open this)
├── api-handler.php    → API logic (don't touch)
├── config.php         → Settings (edit if needed)
├── test.php           → Test installation
└── assets/
    ├── js/app.js      → JavaScript
    └── css/style.css  → Styles
```

## 🔧 Configuration Quick Edit

**Change refresh interval** (config.php):
```php
define('INBOX_REFRESH_INTERVAL', 10000); // 10 seconds
```

**Change session timeout** (config.php):
```php
ini_set('session.gc_maxlifetime', 7200); // 2 hours
```

**Enable debug mode** (config.php):
```php
ini_set('display_errors', 1); // Show errors
```

## 🌐 Browser Compatibility

| Browser | Version | Status |
|---------|---------|--------|
| Chrome | 90+ | ✅ Full support |
| Firefox | 88+ | ✅ Full support |
| Safari | 14+ | ✅ Full support |
| Edge | 90+ | ✅ Full support |
| Opera | 76+ | ✅ Full support |

## 📊 System Requirements

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| **PHP** | 7.4 | 8.0+ |
| **cURL** | Enabled | Enabled |
| **Memory** | 64 MB | 128 MB |
| **Disk** | 1 MB | 10 MB |
| **Internet** | Required | Required |

## 🔐 Security Notes

⚠️ **Important:**
- Temporary emails are **PUBLIC**
- Anyone with the address can read your emails
- **Never use for:**
  - Banking
  - Personal information
  - Password resets (sensitive accounts)
  - Legal documents
  
✅ **Good uses:**
- Testing websites
- One-time signups
- Avoiding spam
- Development/testing

## 📈 API Information

### Mail.tm (Primary)
- **Endpoint:** `https://api.mail.tm`
- **Rate Limit:** Generous (no key needed)
- **Features:** Full email support, authentication
- **Retention:** ~7 days

### 1secmail (Fallback)
- **Endpoint:** `https://www.1secmail.com/api/v1/`
- **Rate Limit:** Unlimited
- **Features:** Basic email support
- **Retention:** ~1 hour

## 🆘 Getting Help

1. **Run test page:** `http://localhost/temp-mail/test.php`
2. **Check console:** Press `F12` → Console tab
3. **Check error log:** `error.log` in project folder
4. **Read docs:** `README.md` and `QUICKSTART.md`
5. **GitHub Issues:** Report bugs with details

## 📝 Quick Testing

```bash
# Test 1: PHP working?
php -v

# Test 2: cURL enabled?
php -m | grep curl

# Test 3: Files readable?
ls -l /path/to/htdocs/temp-mail

# Test 4: Apache running?
netstat -an | grep :80

# Test 5: Can access?
curl http://localhost/temp-mail
```

## 🎨 Customization

### Change Colors
Edit `index.php`, find:
```html
bg-indigo-600  → Change to: bg-purple-600
text-indigo-600 → Change to: text-purple-600
```

### Change Logo
Edit `index.php`, find:
```html
<span class="text-4xl">📧</span>
```
Replace 📧 with your emoji/icon.

### Add Custom CSS
Edit `assets/css/style.css`, add at bottom:
```css
/* Your custom styles */
.my-custom-class {
    /* styles here */
}
```

## 📞 Support Checklist

When asking for help, provide:
- [ ] PHP version (`php -v`)
- [ ] Operating system
- [ ] Browser and version
- [ ] Error messages
- [ ] Screenshot
- [ ] Steps to reproduce
- [ ] test.php results

## ⭐ Quick Commands

```bash
# Start Apache (Linux/Mac)
sudo /opt/lampp/lampp start

# Start Apache (Windows XAMPP)
# Use XAMPP Control Panel

# Check PHP version
php -v

# Check PHP modules
php -m

# Start PHP built-in server
php -S localhost:8000

# View error log
tail -f error.log

# Check file permissions
ls -la
```

## 🔄 Updating

```bash
# 1. Backup current installation
cp -r temp-mail temp-mail.backup

# 2. Download latest version
git pull origin main

# 3. Test
# Open: http://localhost/temp-mail/test.php

# 4. If issues, restore backup
rm -rf temp-mail
mv temp-mail.backup temp-mail
```

## 📌 Bookmarklet (Optional)

Add this to bookmarks for quick access:
```javascript
javascript:(function(){window.open('http://localhost/temp-mail','_blank','width=800,height=600')})()
```

## 🎯 Performance Tips

1. **Use HTTPS** for production (better performance + clipboard works)
2. **Enable gzip** in Apache for faster page loads
3. **Use caching** headers for static assets
4. **Minimize PHP errors** (fix all warnings)
5. **Keep PHP updated** (latest version = faster)

## 🏆 Best Practices

✅ **DO:**
- Use for testing
- Use for one-time signups
- Copy important emails elsewhere
- Delete email when done

❌ **DON'T:**
- Use for sensitive data
- Share email publicly
- Rely on long-term storage
- Use for important accounts

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| `README.md` | Full documentation (10 min read) |
| `QUICKSTART.md` | Beginner guide (5 min read) |
| `CONTRIBUTING.md` | For contributors (dev guide) |
| `PROJECT_SUMMARY.md` | Technical overview |
| `CHECKLIST.md` | Testing checklist |
| `QUICK_REFERENCE.md` | This file (1 min read) |

---

**Made with ❤️ | No configuration needed | Ready in 30 seconds**

For detailed documentation, see: [README.md](README.md)
