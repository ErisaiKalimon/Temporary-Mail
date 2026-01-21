<?php
/**
 * Main Index File for Temp Mail Service
 * 
 * Serves the main application interface
 */

require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temp Mail - Temporary Email Service</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📧</text></svg>">
    
    <style>
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        /* Toast notifications */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
        }
        
        /* Email content iframe styling */
        .email-content-frame {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            min-height: 400px;
        }
        
        /* Scrollbar styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    
    <!-- Toast Container -->
    <div id="toast-container" class="toast"></div>
    
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-4xl">📧</span>
                    <h1 class="text-3xl font-bold text-indigo-600">Temp Mail</h1>
                </div>
                <p class="text-gray-600 text-sm hidden md:block">Temporary Email Service - No Registration Required</p>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        
        <!-- Email Address Display Section -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <div class="flex-1 w-full md:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Temporary Email Address:</label>
                    <div class="flex items-center space-x-2">
                        <input 
                            type="text" 
                            id="email-address" 
                            class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 text-lg font-mono bg-gray-50"
                            value="Loading..."
                            readonly
                        >
                        <button 
                            id="copy-btn" 
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 font-medium"
                            title="Copy to clipboard"
                        >
                            📋 Copy
                        </button>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button 
                        id="refresh-email-btn" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium"
                        title="Generate new email"
                    >
                        🔄 New Email
                    </button>
                </div>
            </div>
            
            <!-- Email Info -->
            <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center space-x-4">
                    <span>📊 API: <span id="api-type" class="font-medium">-</span></span>
                    <span>⏰ Created: <span id="created-time" class="font-medium">-</span></span>
                </div>
                <div class="flex items-center space-x-2">
                    <span id="auto-refresh-status" class="text-green-600 font-medium">● Auto-refresh: ON</span>
                </div>
            </div>
        </section>
        
        <!-- Inbox Section -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    📬 Inbox (<span id="message-count">0</span>)
                </h2>
                <button 
                    id="manual-refresh-btn" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium flex items-center space-x-2"
                >
                    <span id="refresh-icon">🔄</span>
                    <span>Refresh</span>
                </button>
            </div>
            
            <!-- Loading State -->
            <div id="inbox-loading" class="text-center py-12 hidden">
                <div class="inline-block w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full spinner"></div>
                <p class="mt-4 text-gray-600">Loading messages...</p>
            </div>
            
            <!-- Empty State -->
            <div id="inbox-empty" class="text-center py-12 hidden">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No messages yet</h3>
                <p class="text-gray-600">Your inbox is empty. Messages will appear here automatically.</p>
            </div>
            
            <!-- Messages List -->
            <div id="messages-list" class="space-y-2 custom-scrollbar overflow-y-auto max-h-96">
                <!-- Messages will be dynamically inserted here -->
            </div>
        </section>
        
    </main>
    
    <!-- Modal for Reading Email -->
    <div id="email-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4" style="display: none;">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-indigo-600 text-white p-4 flex items-center justify-between">
                <h3 class="text-xl font-bold">📨 Email Details</h3>
                <button id="close-modal-btn" class="text-white hover:text-gray-200 text-2xl leading-none">
                    ×
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                <!-- Loading State -->
                <div id="modal-loading" class="text-center py-12">
                    <div class="inline-block w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full spinner"></div>
                    <p class="mt-4 text-gray-600">Loading message...</p>
                </div>
                
                <!-- Email Content -->
                <div id="modal-content" class="hidden">
                    <div class="mb-6 space-y-3">
                        <div class="flex items-start">
                            <span class="font-semibold text-gray-700 w-24">From:</span>
                            <span id="modal-from" class="text-gray-900"></span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-semibold text-gray-700 w-24">Subject:</span>
                            <span id="modal-subject" class="text-gray-900 font-medium"></span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-semibold text-gray-700 w-24">Date:</span>
                            <span id="modal-date" class="text-gray-600"></span>
                        </div>
                    </div>
                    
                    <hr class="my-4 border-gray-300">
                    
                    <div class="mt-4">
                        <h4 class="font-semibold text-gray-700 mb-3">Message:</h4>
                        <div id="modal-body" class="prose max-w-none bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <!-- Email body will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 p-4 flex justify-end">
                <button id="close-modal-footer-btn" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="mt-12 pb-8 text-center text-gray-600">
        <div class="container mx-auto px-4">
            <p class="mb-2">⚡ Powered by Mail.tm & 1secmail APIs</p>
            <p class="text-sm">No registration required • Fully anonymous • Auto-refresh inbox</p>
            <p class="text-xs mt-4 text-gray-500">⚠️ Temporary emails are public and expire after some time. Do not use for sensitive information.</p>
        </div>
    </footer>
    
    <!-- Main JavaScript -->
    <script>
        // Pass PHP constants to JavaScript
        const REFRESH_INTERVAL = <?php echo INBOX_REFRESH_INTERVAL; ?>;
    </script>
    <script src="assets/js/app.js"></script>
    
</body>
</html>
