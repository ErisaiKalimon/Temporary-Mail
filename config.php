<?php
/**
 * Configuration File for Temp Mail Service
 * 
 * Contains API endpoints and configuration constants
 */

// Session configuration
ini_set('session.gc_maxlifetime', 3600); // 1 hour
session_start();

// API Configuration
define('MAIL_TM_API_BASE', 'https://api.mail.tm');
define('ONESECMAIL_API_BASE', 'https://www.1secmail.com/api/v1/');

// Default domain for 1secmail
define('ONESECMAIL_DOMAINS', ['1secmail.com', '1secmail.org', '1secmail.net']);

// Refresh interval in milliseconds (5 seconds)
define('INBOX_REFRESH_INTERVAL', 5000);

// Error messages
define('ERROR_API_UNAVAILABLE', 'API service is currently unavailable. Please try again later.');
define('ERROR_EMAIL_GENERATION', 'Failed to generate email address. Please try again.');
define('ERROR_FETCH_MESSAGES', 'Failed to fetch messages. Please refresh the page.');
define('ERROR_READ_MESSAGE', 'Failed to read message content.');
define('ERROR_INVALID_REQUEST', 'Invalid request.');

// Success messages
define('SUCCESS_EMAIL_GENERATED', 'New email address generated successfully!');
define('SUCCESS_EMAIL_COPIED', 'Email address copied to clipboard!');

// Timezone
date_default_timezone_set('UTC');

// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to users
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
