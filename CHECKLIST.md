# ✅ Setup & Testing Checklist

Use this checklist to verify your Temp Mail installation is working correctly.

## Pre-Installation Checklist

- [ ] XAMPP/Laragon/Web server installed
- [ ] Apache/Web server running
- [ ] PHP 7.4+ installed
- [ ] cURL extension enabled
- [ ] Project files downloaded/cloned

## Installation Checklist

- [ ] Files placed in correct directory (htdocs/www)
- [ ] All files present:
  - [ ] index.php
  - [ ] api-handler.php
  - [ ] config.php
  - [ ] test.php
  - [ ] assets/js/app.js
  - [ ] assets/css/style.css
  - [ ] README.md
- [ ] File permissions correct (readable by web server)

## Verification Checklist

### Step 1: Basic Server Test
- [ ] Can access: `http://localhost/temp-mail/test.php`
- [ ] All tests show green checkmarks ✅
- [ ] No red error marks ❌

### Step 2: Application Load
- [ ] Can access: `http://localhost/temp-mail`
- [ ] Page loads without errors
- [ ] No blank white screen
- [ ] Tailwind CSS loads (styled interface)
- [ ] No console errors (F12 → Console)

### Step 3: Email Generation
- [ ] Email address appears automatically on first load
- [ ] Email address format is valid (xxx@domain.com)
- [ ] API type displays (Mail.tm or 1secmail)
- [ ] Created timestamp displays
- [ ] No error messages

### Step 4: Copy to Clipboard
- [ ] Click "Copy" button
- [ ] Toast notification appears: "Email copied to clipboard!"
- [ ] Button changes to "✅ Copied!" briefly
- [ ] Can paste email address successfully

### Step 5: Inbox Display
- [ ] Inbox section visible
- [ ] Message count shows (0)
- [ ] Empty state message displays
- [ ] Auto-refresh status shows green
- [ ] No JavaScript errors

### Step 6: Send Test Email
- [ ] Open Gmail/Yahoo/Outlook
- [ ] Send email to generated temp address
- [ ] Include subject and body content

### Step 7: Auto-Refresh & Receive
- [ ] Wait 5-10 seconds
- [ ] Refresh icon spins briefly
- [ ] New email appears in inbox automatically
- [ ] Message count updates
- [ ] Empty state disappears
- [ ] Email shows: sender, subject, timestamp

### Step 8: Read Email
- [ ] Click on email in inbox
- [ ] Modal opens
- [ ] Email content loads
- [ ] Sender displays correctly
- [ ] Subject displays correctly
- [ ] Date/time displays
- [ ] Body content renders (HTML or text)
- [ ] No formatting issues

### Step 9: Modal Controls
- [ ] Can close modal with "Close" button (top)
- [ ] Can close modal with "Close" button (bottom)
- [ ] Can close modal with ESC key
- [ ] Can close modal by clicking background
- [ ] Modal closes smoothly

### Step 10: Manual Refresh
- [ ] Click "Refresh" button
- [ ] Refresh icon spins
- [ ] Inbox updates
- [ ] No errors

### Step 11: Generate New Email
- [ ] Click "New Email" button
- [ ] Confirmation dialog appears
- [ ] Click "OK" to confirm
- [ ] New email address generated
- [ ] Different from previous email
- [ ] Inbox clears
- [ ] Message count resets to 0
- [ ] Toast notification shows success

### Step 12: Session Persistence
- [ ] Note current email address
- [ ] Refresh browser (F5)
- [ ] Same email address persists
- [ ] Previous messages still visible

### Step 13: Mobile Responsiveness
- [ ] Open browser DevTools (F12)
- [ ] Toggle device toolbar (Ctrl+Shift+M)
- [ ] Test iPhone view
  - [ ] Layout looks good
  - [ ] Buttons are clickable
  - [ ] Modal fits screen
  - [ ] Can scroll inbox
- [ ] Test iPad view
  - [ ] Layout adapts correctly
  - [ ] All features work
- [ ] Test Android phone view
  - [ ] Layout responsive
  - [ ] Touch targets adequate

### Step 14: Browser Compatibility
- [ ] Test in Chrome
  - [ ] All features work
  - [ ] No console errors
- [ ] Test in Firefox (if available)
  - [ ] All features work
  - [ ] No console errors
- [ ] Test in Edge (if available)
  - [ ] All features work
  - [ ] No console errors
- [ ] Test in Safari (if on Mac)
  - [ ] All features work
  - [ ] No console errors

### Step 15: Error Handling
- [ ] Disconnect internet
- [ ] Try to generate new email
- [ ] Error message displays gracefully
- [ ] No application crash
- [ ] Reconnect internet
- [ ] Application recovers
- [ ] Can generate email again

## Post-Testing Checklist

- [ ] All core features working
- [ ] No console errors
- [ ] No PHP errors in error.log
- [ ] Performance acceptable (fast load times)
- [ ] UI looks professional
- [ ] Mobile view works
- [ ] Ready for use

## Optional: Advanced Testing

### Performance Testing
- [ ] Page load time < 2 seconds
- [ ] API calls complete quickly
- [ ] No memory leaks (check DevTools Memory)
- [ ] Smooth animations

### Security Testing
- [ ] HTML content sanitized (view email with <script> tags)
- [ ] External links have rel="noopener noreferrer"
- [ ] No XSS vulnerabilities
- [ ] Sessions work correctly

### Accessibility Testing
- [ ] Can tab through all controls
- [ ] Focus indicators visible
- [ ] Contrast ratios acceptable
- [ ] Screen reader friendly (if possible)

## Troubleshooting

If any checkbox fails:

1. **Email not generating:**
   - Check internet connection
   - Verify cURL enabled: `php -m | grep curl`
   - Check test.php for issues
   - Review error.log file

2. **Page not loading:**
   - Verify Apache running
   - Check correct URL: `http://localhost/temp-mail`
   - Verify files in htdocs/www folder
   - Check PHP version: `php -v`

3. **Console errors:**
   - Open browser console (F12)
   - Read error messages
   - Check if app.js loaded
   - Verify Tailwind CSS CDN accessible

4. **Copy not working:**
   - Try HTTPS instead of HTTP
   - Grant clipboard permissions
   - Use fallback: manually select and copy

5. **Messages not appearing:**
   - Wait 10-30 seconds (delivery time)
   - Click manual refresh
   - Check if email sent successfully
   - Try different sender

6. **Modal not opening:**
   - Check browser console for errors
   - Verify JavaScript is enabled
   - Try different browser
   - Clear cache and reload

## Need Help?

If issues persist:

1. Read **README.md** Troubleshooting section
2. Check **test.php** results
3. Review **QUICKSTART.md** guide
4. Check browser console (F12)
5. Check error.log file
6. Open GitHub issue with:
   - Checklist items that failed
   - Error messages
   - Browser and OS info
   - PHP version
   - Screenshots

## Success Criteria

✅ **Installation Successful** if:
- test.php shows all green ✅
- Application loads without errors
- Can generate email address
- Can receive and read emails
- Copy to clipboard works
- Auto-refresh works
- Mobile view works

---

**Congratulations! 🎉**

If you've checked all boxes above, your Temp Mail installation is working perfectly!

**Next Steps:**
1. Bookmark the application
2. Share with others
3. Star the GitHub repo ⭐
4. Report any bugs
5. Contribute improvements

**Remember:** Use temporary emails for testing only, not for sensitive information!
