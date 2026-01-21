# 🤝 Contributing to Temp Mail Service

Thank you for considering contributing to Temp Mail! We welcome contributions from everyone.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Pull Request Process](#pull-request-process)
- [Bug Reports](#bug-reports)
- [Feature Requests](#feature-requests)

## Code of Conduct

### Our Pledge

We are committed to providing a welcoming and inclusive environment for all contributors.

### Our Standards

- ✅ Use welcoming and inclusive language
- ✅ Be respectful of differing viewpoints
- ✅ Accept constructive criticism gracefully
- ✅ Focus on what's best for the community

## How Can I Contribute?

### 1. Reporting Bugs

Before creating a bug report:
- Check existing issues to avoid duplicates
- Try the latest version
- Collect relevant information (PHP version, browser, OS)

**Bug Report Template:**
```markdown
**Describe the bug**
A clear description of what the bug is.

**To Reproduce**
Steps to reproduce the behavior:
1. Go to '...'
2. Click on '...'
3. See error

**Expected behavior**
What you expected to happen.

**Screenshots**
If applicable, add screenshots.

**Environment:**
- OS: [e.g., Windows 10]
- Browser: [e.g., Chrome 96]
- PHP Version: [e.g., 8.0]
- XAMPP/Laragon Version: [e.g., 8.1.0]

**Additional context**
Any other information about the problem.
```

### 2. Suggesting Features

**Feature Request Template:**
```markdown
**Is your feature request related to a problem?**
A clear description of the problem.

**Describe the solution you'd like**
A clear description of what you want to happen.

**Describe alternatives you've considered**
Alternative solutions or features you've considered.

**Additional context**
Any other context or screenshots about the feature request.
```

### 3. Code Contributions

We welcome code contributions! Here's what we're looking for:

**High Priority:**
- Bug fixes
- Security improvements
- Performance optimizations
- Documentation improvements

**Medium Priority:**
- New email provider integrations
- UI/UX enhancements
- Mobile responsiveness improvements
- Accessibility features

**Low Priority:**
- New features (discuss first)
- Code refactoring
- Additional styling options

## Development Setup

### Prerequisites

- PHP 7.4+ (8.0+ recommended)
- Git
- Web server (Apache/Nginx) or PHP built-in server
- Modern web browser
- Text editor or IDE (VS Code, PhpStorm, etc.)

### Setup Steps

1. **Fork the repository**
   - Click "Fork" button on GitHub
   - Clone your fork locally

2. **Clone and setup**
   ```bash
   git clone https://github.com/YOUR_USERNAME/temp-mail.git
   cd temp-mail
   
   # Create a new branch for your feature
   git checkout -b feature/your-feature-name
   ```

3. **Configure development environment**
   ```bash
   # Copy environment file (if needed)
   cp .env.example .env
   
   # Start development server
   php -S localhost:8000
   # or use XAMPP/Laragon
   ```

4. **Make your changes**
   - Write code
   - Test thoroughly
   - Update documentation if needed

5. **Commit your changes**
   ```bash
   git add .
   git commit -m "Add feature: Your feature description"
   ```

6. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Create Pull Request**
   - Go to original repository on GitHub
   - Click "New Pull Request"
   - Select your branch
   - Fill in PR template

## Coding Standards

### PHP Standards

We follow **PSR-12** coding standards.

**Key points:**
- Use 4 spaces for indentation (no tabs)
- Opening braces on same line for functions/classes
- Always use strict types where possible
- Document all functions with PHPDoc

**Example:**
```php
<?php

/**
 * Generate random username
 * 
 * @param int $length Length of username
 * @return string Generated username
 */
function generateUsername(int $length = 10): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $username = '';
    
    for ($i = 0; $i < $length; $i++) {
        $username .= $chars[rand(0, strlen($chars) - 1)];
    }
    
    return $username;
}
```

### JavaScript Standards

We follow **ES6+** standards with ESLint.

**Key points:**
- Use `const` and `let`, avoid `var`
- Use arrow functions where appropriate
- Use async/await over promises
- Always use semicolons
- Use template literals for strings

**Example:**
```javascript
/**
 * Fetch messages from API
 * @returns {Promise<Array>} Array of messages
 */
async fetchMessages() {
    try {
        const response = await fetch(`/api?action=get_messages`);
        const data = await response.json();
        return data.messages || [];
    } catch (error) {
        console.error('Error fetching messages:', error);
        return [];
    }
}
```

### CSS Standards

**Key points:**
- Use Tailwind utility classes first
- Custom CSS only when necessary
- Mobile-first approach
- Use CSS variables for theme colors
- Comment complex selectors

### HTML Standards

**Key points:**
- Use semantic HTML5 elements
- Include ARIA labels for accessibility
- Validate markup
- Optimize for SEO

## Testing

Before submitting a PR, test:

### Manual Testing Checklist

- [ ] Email generation works
- [ ] Copy to clipboard works (HTTPS and HTTP)
- [ ] Inbox auto-refresh works
- [ ] Manual refresh works
- [ ] Reading emails works (HTML and text)
- [ ] Modal opens and closes
- [ ] New email generation works
- [ ] Mobile responsive (Chrome DevTools)
- [ ] Works in multiple browsers
- [ ] No console errors
- [ ] No PHP errors in error.log

### Browser Testing

Test in:
- Chrome (latest)
- Firefox (latest)
- Safari (latest, if on Mac)
- Edge (latest)
- Mobile browsers (Chrome Mobile, Safari Mobile)

### Performance Testing

- [ ] Page loads in < 2 seconds
- [ ] API calls complete in < 5 seconds
- [ ] No memory leaks in browser
- [ ] Efficient DOM updates

## Pull Request Process

### PR Checklist

Before submitting:

- [ ] Code follows project coding standards
- [ ] All tests pass
- [ ] Documentation is updated
- [ ] Commit messages are clear and descriptive
- [ ] Branch is up to date with main
- [ ] No merge conflicts
- [ ] PR description explains changes clearly

### PR Template

```markdown
## Description
Brief description of changes.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## How Has This Been Tested?
Describe testing process.

## Screenshots (if applicable)
Add screenshots of UI changes.

## Checklist
- [ ] My code follows the project style
- [ ] I have performed a self-review
- [ ] I have commented my code
- [ ] I have updated documentation
- [ ] My changes generate no new warnings
- [ ] I have tested on multiple browsers
```

### Review Process

1. **Initial Review** (1-3 days)
   - Maintainer reviews code
   - Provides feedback if needed

2. **Revisions** (if requested)
   - Make requested changes
   - Push to same branch
   - Request re-review

3. **Approval & Merge**
   - Once approved, maintainer merges
   - PR is closed
   - Branch can be deleted

## Code Review Guidelines

### For Contributors

- Be open to feedback
- Respond promptly to review comments
- Keep PRs focused and small
- Don't take criticism personally

### For Reviewers

- Be constructive and respectful
- Explain reasoning for suggestions
- Approve when ready, request changes when needed
- Recognize good work

## Project Structure

Understanding the codebase:

```
temp-mail/
├── index.php           # Main UI page
├── api-handler.php     # API logic & business rules
├── config.php          # Configuration settings
├── assets/
│   ├── js/
│   │   └── app.js     # Frontend JavaScript
│   └── css/
│       └── style.css  # Custom styles
└── docs/               # Documentation
```

### Key Files to Know

- **index.php**: HTML structure, UI components
- **api-handler.php**: TempMailAPI class, API integration
- **app.js**: TempMailApp class, frontend logic
- **config.php**: Constants, settings, API endpoints

## Commit Message Guidelines

Use conventional commits format:

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation
- `style`: Formatting, missing semicolons, etc.
- `refactor`: Code restructuring
- `test`: Adding tests
- `chore`: Maintenance

**Examples:**
```
feat(api): add support for new email provider

fix(ui): resolve mobile layout issue on iPhone

docs(readme): update installation instructions

style(css): improve button hover animations
```

## Getting Help

Need help contributing?

- 💬 Start a discussion on GitHub
- 📧 Contact maintainers
- 📖 Read existing code and comments
- 🔍 Check closed PRs for examples

## Recognition

Contributors will be:
- Listed in CONTRIBUTORS.md
- Mentioned in release notes
- Credited in commit history

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

**Thank you for contributing! 🎉**

Every contribution, no matter how small, makes a difference!
