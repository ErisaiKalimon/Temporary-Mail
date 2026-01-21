/**
 * Main JavaScript Application for Temp Mail Service
 * 
 * Handles all frontend functionality including:
 * - Email generation and display
 * - Inbox auto-refresh
 * - Message reading
 * - Copy to clipboard
 * - UI interactions
 */

class TempMailApp {
    constructor() {
        this.currentEmail = null;
        this.refreshInterval = null;
        this.isRefreshing = false;
        this.currentMessages = [];
        
        // Initialize the application
        this.init();
    }
    
    /**
     * Initialize the application
     */
    init() {
        console.log('🚀 Initializing Temp Mail App...');
        
        // Set up event listeners
        this.setupEventListeners();
        
        // Load existing email or generate new one
        this.loadOrGenerateEmail();
        
        // Start auto-refresh
        this.startAutoRefresh();
    }
    
    /**
     * Set up event listeners for UI interactions
     */
    setupEventListeners() {
        // Copy button
        document.getElementById('copy-btn').addEventListener('click', () => {
            this.copyEmailToClipboard();
        });
        
        // Refresh email button (generate new)
        document.getElementById('refresh-email-btn').addEventListener('click', () => {
            this.confirmAndRegenerateEmail();
        });
        
        // Manual refresh button
        document.getElementById('manual-refresh-btn').addEventListener('click', () => {
            this.refreshInbox();
        });
        
        // Modal close buttons
        document.getElementById('close-modal-btn').addEventListener('click', () => {
            this.closeModal();
        });
        
        document.getElementById('close-modal-footer-btn').addEventListener('click', () => {
            this.closeModal();
        });
        
        // Close modal on background click
        document.getElementById('email-modal').addEventListener('click', (e) => {
            if (e.target.id === 'email-modal') {
                this.closeModal();
            }
        });
        
        // ESC key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }
    
    /**
     * Load existing email from server or generate new one
     */
    async loadOrGenerateEmail() {
        try {
            // Try to get current email from session
            const response = await this.apiCall('get_current');
            
            if (response.success && response.email) {
                this.currentEmail = response.email;
                this.displayEmail(response);
                this.refreshInbox();
            } else {
                // No existing email, generate new one
                await this.generateNewEmail();
            }
        } catch (error) {
            console.error('Error loading email:', error);
            this.showToast('Error loading email. Generating new one...', 'error');
            await this.generateNewEmail();
        }
    }
    
    /**
     * Generate new temporary email
     */
    async generateNewEmail() {
        console.log('📧 Generating new email...');
        
        try {
            const response = await this.apiCall('generate');
            
            if (response.success && response.email) {
                this.currentEmail = response.email;
                this.displayEmail(response);
                this.showToast('✅ New email generated successfully!', 'success');
                
                // Clear messages and refresh
                this.clearMessages();
                this.refreshInbox();
            } else {
                throw new Error(response.error || 'Failed to generate email');
            }
        } catch (error) {
            console.error('Error generating email:', error);
            this.showToast('❌ Failed to generate email. Please try again.', 'error');
        }
    }
    
    /**
     * Display email address and info
     */
    displayEmail(data) {
        const emailInput = document.getElementById('email-address');
        const apiType = document.getElementById('api-type');
        const createdTime = document.getElementById('created-time');
        
        emailInput.value = data.email;
        apiType.textContent = data.api_type === 'mailtm' ? 'Mail.tm' : '1secmail';
        
        if (data.created_at) {
            const date = new Date(data.created_at * 1000);
            createdTime.textContent = this.formatDate(date);
        }
    }
    
    /**
     * Copy email address to clipboard
     */
    async copyEmailToClipboard() {
        const emailInput = document.getElementById('email-address');
        const email = emailInput.value;
        
        try {
            // Modern clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(email);
            } else {
                // Fallback for older browsers
                emailInput.select();
                document.execCommand('copy');
            }
            
            this.showToast('📋 Email copied to clipboard!', 'success');
            
            // Visual feedback on button
            const copyBtn = document.getElementById('copy-btn');
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '✅ Copied!';
            copyBtn.classList.add('bg-green-600');
            copyBtn.classList.remove('bg-indigo-600');
            
            setTimeout(() => {
                copyBtn.innerHTML = originalText;
                copyBtn.classList.remove('bg-green-600');
                copyBtn.classList.add('bg-indigo-600');
            }, 2000);
            
        } catch (error) {
            console.error('Error copying to clipboard:', error);
            this.showToast('❌ Failed to copy email', 'error');
        }
    }
    
    /**
     * Confirm and regenerate email
     */
    confirmAndRegenerateEmail() {
        const confirmed = confirm('Are you sure you want to generate a new email address? Your current email and all messages will be lost.');
        
        if (confirmed) {
            this.regenerateEmail();
        }
    }
    
    /**
     * Regenerate email (delete current and create new)
     */
    async regenerateEmail() {
        console.log('🔄 Regenerating email...');
        
        try {
            const response = await this.apiCall('delete_regenerate');
            
            if (response.success && response.email) {
                this.currentEmail = response.email;
                this.displayEmail(response);
                this.clearMessages();
                this.showToast('✅ New email generated!', 'success');
                this.refreshInbox();
            } else {
                throw new Error(response.error || 'Failed to regenerate email');
            }
        } catch (error) {
            console.error('Error regenerating email:', error);
            this.showToast('❌ Failed to regenerate email', 'error');
        }
    }
    
    /**
     * Start auto-refresh interval
     */
    startAutoRefresh() {
        console.log(`⏰ Starting auto-refresh (every ${REFRESH_INTERVAL/1000}s)`);
        
        this.refreshInterval = setInterval(() => {
            this.refreshInbox();
        }, REFRESH_INTERVAL);
    }
    
    /**
     * Stop auto-refresh interval
     */
    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
    }
    
    /**
     * Refresh inbox messages
     */
    async refreshInbox() {
        if (this.isRefreshing) {
            return; // Prevent multiple simultaneous refreshes
        }
        
        this.isRefreshing = true;
        
        // Show loading indicator on button
        const refreshIcon = document.getElementById('refresh-icon');
        refreshIcon.classList.add('spinner');
        
        try {
            const response = await this.apiCall('get_messages');
            
            if (response.success) {
                this.currentMessages = response.messages || [];
                this.displayMessages(this.currentMessages);
                this.updateMessageCount(response.count || 0);
            } else {
                console.error('Error fetching messages:', response.error);
                // Don't show error toast for auto-refresh failures
            }
        } catch (error) {
            console.error('Error refreshing inbox:', error);
        } finally {
            this.isRefreshing = false;
            refreshIcon.classList.remove('spinner');
        }
    }
    
    /**
     * Display messages in the inbox
     */
    displayMessages(messages) {
        const messagesList = document.getElementById('messages-list');
        const emptyState = document.getElementById('inbox-empty');
        const loadingState = document.getElementById('inbox-loading');
        
        // Hide loading state
        loadingState.classList.add('hidden');
        
        if (messages.length === 0) {
            messagesList.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        // Sort messages by date (newest first)
        messages.sort((a, b) => new Date(b.date) - new Date(a.date));
        
        messagesList.innerHTML = messages.map(msg => `
            <div class="message-item border-2 border-gray-200 rounded-lg p-4 hover:border-indigo-500 hover:bg-indigo-50 cursor-pointer transition-all duration-200 fade-in"
                 onclick="app.openMessage('${this.escapeHtml(msg.id)}')">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="font-semibold text-gray-900">${this.escapeHtml(msg.from_name || msg.from)}</span>
                            ${!msg.seen ? '<span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">New</span>' : ''}
                        </div>
                        <p class="text-sm text-gray-600 mb-1">${this.escapeHtml(msg.from)}</p>
                        <p class="text-gray-900 font-medium">${this.escapeHtml(msg.subject)}</p>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm text-gray-500">${this.formatDate(new Date(msg.date))}</p>
                        <span class="text-2xl">📧</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    /**
     * Clear all messages from display
     */
    clearMessages() {
        this.currentMessages = [];
        const messagesList = document.getElementById('messages-list');
        messagesList.innerHTML = '';
        
        const emptyState = document.getElementById('inbox-empty');
        emptyState.classList.remove('hidden');
        
        this.updateMessageCount(0);
    }
    
    /**
     * Update message count display
     */
    updateMessageCount(count) {
        document.getElementById('message-count').textContent = count;
    }
    
    /**
     * Open message in modal
     */
    async openMessage(messageId) {
        console.log('📨 Opening message:', messageId);
        
        const modal = document.getElementById('email-modal');
        const modalLoading = document.getElementById('modal-loading');
        const modalContent = document.getElementById('modal-content');
        
        // Show modal with loading state
        modal.style.display = 'flex';
        modalLoading.classList.remove('hidden');
        modalContent.classList.add('hidden');
        
        try {
            const response = await this.apiCall('read_message', { id: messageId });
            
            if (response.success && response.message) {
                this.displayMessageInModal(response.message);
            } else {
                throw new Error(response.error || 'Failed to read message');
            }
        } catch (error) {
            console.error('Error reading message:', error);
            this.showToast('❌ Failed to load message', 'error');
            this.closeModal();
        }
    }
    
    /**
     * Display message content in modal
     */
    displayMessageInModal(message) {
        const modalLoading = document.getElementById('modal-loading');
        const modalContent = document.getElementById('modal-content');
        
        document.getElementById('modal-from').textContent = message.from_name ? 
            `${message.from_name} <${message.from}>` : message.from;
        document.getElementById('modal-subject').textContent = message.subject;
        document.getElementById('modal-date').textContent = this.formatDate(new Date(message.date));
        
        const modalBody = document.getElementById('modal-body');
        
        // Display HTML content if available, otherwise text content
        if (message.html) {
            // Sanitize and display HTML
            modalBody.innerHTML = this.sanitizeHtml(message.html);
        } else {
            // Display as plain text
            modalBody.innerHTML = `<pre class="whitespace-pre-wrap font-sans">${this.escapeHtml(message.text)}</pre>`;
        }
        
        // Hide loading, show content
        modalLoading.classList.add('hidden');
        modalContent.classList.remove('hidden');
    }
    
    /**
     * Close modal
     */
    closeModal() {
        const modal = document.getElementById('email-modal');
        modal.style.display = 'none';
    }
    
    /**
     * Show toast notification
     */
    showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500',
            warning: 'bg-yellow-500'
        };
        
        const toast = document.createElement('div');
        toast.className = `${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg mb-2 fade-in`;
        toast.textContent = message;
        
        container.appendChild(toast);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.3s ease-out';
            
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
    
    /**
     * Make API call
     */
    async apiCall(action, params = {}) {
        const url = new URL('api-handler.php', window.location.href);
        url.searchParams.append('action', action);
        
        // Add additional parameters
        Object.keys(params).forEach(key => {
            url.searchParams.append(key, params[key]);
        });
        
        const response = await fetch(url.toString(), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    }
    
    /**
     * Format date to readable string
     */
    formatDate(date) {
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);
        
        if (diffMins < 1) {
            return 'Just now';
        } else if (diffMins < 60) {
            return `${diffMins} min${diffMins > 1 ? 's' : ''} ago`;
        } else if (diffHours < 24) {
            return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
        } else if (diffDays < 7) {
            return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
        } else {
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        }
    }
    
    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }
    
    /**
     * Basic HTML sanitization (remove dangerous tags and attributes)
     */
    sanitizeHtml(html) {
        // Create a temporary div to parse HTML
        const temp = document.createElement('div');
        temp.innerHTML = html;
        
        // Remove dangerous elements
        const dangerousTags = ['script', 'iframe', 'object', 'embed', 'link', 'style'];
        dangerousTags.forEach(tag => {
            const elements = temp.querySelectorAll(tag);
            elements.forEach(el => el.remove());
        });
        
        // Remove dangerous attributes
        const dangerousAttrs = ['onclick', 'onload', 'onerror', 'onmouseover'];
        const allElements = temp.querySelectorAll('*');
        allElements.forEach(el => {
            dangerousAttrs.forEach(attr => {
                if (el.hasAttribute(attr)) {
                    el.removeAttribute(attr);
                }
            });
            
            // Make external links safe
            if (el.tagName === 'A') {
                el.setAttribute('target', '_blank');
                el.setAttribute('rel', 'noopener noreferrer');
            }
        });
        
        return temp.innerHTML;
    }
}

// Initialize the application when DOM is ready
let app;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        app = new TempMailApp();
    });
} else {
    app = new TempMailApp();
}
