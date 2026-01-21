<?php
/**
 * API Handler for Temp Mail Service
 * 
 * Handles all API operations including:
 * - Email generation (Mail.tm and 1secmail)
 * - Fetching inbox messages
 * - Reading individual emails
 * - Deleting/resetting email sessions
 */

require_once 'config.php';

class TempMailAPI {
    private $apiType = 'mailtm'; // 'mailtm' or '1secmail'
    
    /**
     * Make cURL request to external API
     * 
     * @param string $url The API endpoint URL
     * @param string $method HTTP method (GET, POST, DELETE)
     * @param array $data Data to send with request
     * @param array $headers Additional headers
     * @return array Response data
     */
    private function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        // Set custom headers
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        // Set method and data
        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'GET':
            default:
                // GET is default
                break;
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'error' => 'cURL Error: ' . $error
            ];
        }
        
        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'http_code' => $httpCode,
            'data' => $response ? json_decode($response, true) : null,
            'raw' => $response
        ];
    }
    
    /**
     * Generate new temporary email address using Mail.tm
     * 
     * @return array Email data or error
     */
    private function generateMailTmEmail() {
        // First, get available domains
        $domainsResponse = $this->makeRequest(MAIL_TM_API_BASE . '/domains');
        
        if (!$domainsResponse['success'] || empty($domainsResponse['data']['hydra:member'])) {
            return ['success' => false, 'error' => 'Failed to fetch domains'];
        }
        
        $domain = $domainsResponse['data']['hydra:member'][0]['domain'];
        
        // Generate random username
        $username = $this->generateRandomUsername();
        $email = $username . '@' . $domain;
        $password = bin2hex(random_bytes(16));
        
        // Create account
        $accountData = [
            'address' => $email,
            'password' => $password
        ];
        
        $headers = ['Content-Type: application/json'];
        $accountResponse = $this->makeRequest(
            MAIL_TM_API_BASE . '/accounts',
            'POST',
            $accountData,
            $headers
        );
        
        if (!$accountResponse['success']) {
            return ['success' => false, 'error' => 'Failed to create account'];
        }
        
        // Get authentication token
        $authData = [
            'address' => $email,
            'password' => $password
        ];
        
        $tokenResponse = $this->makeRequest(
            MAIL_TM_API_BASE . '/token',
            'POST',
            $authData,
            $headers
        );
        
        if (!$tokenResponse['success'] || empty($tokenResponse['data']['token'])) {
            return ['success' => false, 'error' => 'Failed to get authentication token'];
        }
        
        $token = $tokenResponse['data']['token'];
        $accountId = $accountResponse['data']['id'];
        
        return [
            'success' => true,
            'email' => $email,
            'token' => $token,
            'account_id' => $accountId,
            'password' => $password,
            'api_type' => 'mailtm'
        ];
    }
    
    /**
     * Generate new temporary email address using 1secmail
     * 
     * @return array Email data or error
     */
    private function generate1SecMailEmail() {
        $username = $this->generateRandomUsername();
        $domain = ONESECMAIL_DOMAINS[array_rand(ONESECMAIL_DOMAINS)];
        $email = $username . '@' . $domain;
        
        return [
            'success' => true,
            'email' => $email,
            'username' => $username,
            'domain' => $domain,
            'api_type' => '1secmail'
        ];
    }
    
    /**
     * Generate random username
     * 
     * @return string Random username
     */
    private function generateRandomUsername() {
        $length = rand(8, 12);
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $username = '';
        
        for ($i = 0; $i < $length; $i++) {
            $username .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $username;
    }
    
    /**
     * Generate new temporary email (tries Mail.tm first, then 1secmail)
     * 
     * @return array Email data
     */
    public function generateEmail() {
        // Try Mail.tm first
        $result = $this->generateMailTmEmail();
        
        // If Mail.tm fails, try 1secmail
        if (!$result['success']) {
            $result = $this->generate1SecMailEmail();
        }
        
        if ($result['success']) {
            // Store in session
            $_SESSION['temp_email'] = $result;
            $_SESSION['created_at'] = time();
        }
        
        return $result;
    }
    
    /**
     * Get inbox messages for Mail.tm
     * 
     * @param string $token Authentication token
     * @return array Messages or error
     */
    private function getMailTmMessages($token) {
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];
        
        $response = $this->makeRequest(
            MAIL_TM_API_BASE . '/messages',
            'GET',
            null,
            $headers
        );
        
        if (!$response['success']) {
            return ['success' => false, 'error' => 'Failed to fetch messages'];
        }
        
        $messages = [];
        if (!empty($response['data']['hydra:member'])) {
            foreach ($response['data']['hydra:member'] as $msg) {
                $messages[] = [
                    'id' => $msg['id'],
                    'from' => $msg['from']['address'] ?? 'Unknown',
                    'from_name' => $msg['from']['name'] ?? '',
                    'subject' => $msg['subject'] ?? '(No Subject)',
                    'date' => $msg['createdAt'] ?? date('c'),
                    'seen' => $msg['seen'] ?? false
                ];
            }
        }
        
        return [
            'success' => true,
            'messages' => $messages,
            'count' => count($messages)
        ];
    }
    
    /**
     * Get inbox messages for 1secmail
     * 
     * @param string $username Email username
     * @param string $domain Email domain
     * @return array Messages or error
     */
    private function get1SecMailMessages($username, $domain) {
        $url = ONESECMAIL_API_BASE . '?action=getMessages&login=' . urlencode($username) . '&domain=' . urlencode($domain);
        
        $response = $this->makeRequest($url);
        
        if (!$response['success']) {
            return ['success' => false, 'error' => 'Failed to fetch messages'];
        }
        
        $messages = [];
        $data = $response['data'];
        
        if (is_array($data)) {
            foreach ($data as $msg) {
                $messages[] = [
                    'id' => $msg['id'],
                    'from' => $msg['from'] ?? 'Unknown',
                    'from_name' => '',
                    'subject' => $msg['subject'] ?? '(No Subject)',
                    'date' => $msg['date'] ?? date('c'),
                    'seen' => false
                ];
            }
        }
        
        return [
            'success' => true,
            'messages' => $messages,
            'count' => count($messages)
        ];
    }
    
    /**
     * Get inbox messages
     * 
     * @return array Messages or error
     */
    public function getMessages() {
        if (empty($_SESSION['temp_email'])) {
            return ['success' => false, 'error' => 'No email session found'];
        }
        
        $emailData = $_SESSION['temp_email'];
        
        if ($emailData['api_type'] === 'mailtm') {
            return $this->getMailTmMessages($emailData['token']);
        } else {
            return $this->get1SecMailMessages($emailData['username'], $emailData['domain']);
        }
    }
    
    /**
     * Read single message from Mail.tm
     * 
     * @param string $messageId Message ID
     * @param string $token Authentication token
     * @return array Message content or error
     */
    private function readMailTmMessage($messageId, $token) {
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];
        
        $response = $this->makeRequest(
            MAIL_TM_API_BASE . '/messages/' . $messageId,
            'GET',
            null,
            $headers
        );
        
        if (!$response['success']) {
            return ['success' => false, 'error' => 'Failed to read message'];
        }
        
        $msg = $response['data'];
        
        return [
            'success' => true,
            'message' => [
                'id' => $msg['id'],
                'from' => $msg['from']['address'] ?? 'Unknown',
                'from_name' => $msg['from']['name'] ?? '',
                'subject' => $msg['subject'] ?? '(No Subject)',
                'date' => $msg['createdAt'] ?? date('c'),
                'html' => $msg['html'][0] ?? null,
                'text' => $msg['text'] ?? $msg['intro'] ?? '',
                'seen' => true
            ]
        ];
    }
    
    /**
     * Read single message from 1secmail
     * 
     * @param string $messageId Message ID
     * @param string $username Email username
     * @param string $domain Email domain
     * @return array Message content or error
     */
    private function read1SecMailMessage($messageId, $username, $domain) {
        $url = ONESECMAIL_API_BASE . '?action=readMessage&login=' . urlencode($username) . 
               '&domain=' . urlencode($domain) . '&id=' . urlencode($messageId);
        
        $response = $this->makeRequest($url);
        
        if (!$response['success']) {
            return ['success' => false, 'error' => 'Failed to read message'];
        }
        
        $msg = $response['data'];
        
        return [
            'success' => true,
            'message' => [
                'id' => $msg['id'],
                'from' => $msg['from'] ?? 'Unknown',
                'from_name' => '',
                'subject' => $msg['subject'] ?? '(No Subject)',
                'date' => $msg['date'] ?? date('c'),
                'html' => $msg['htmlBody'] ?? null,
                'text' => $msg['textBody'] ?? $msg['body'] ?? '',
                'seen' => true
            ]
        ];
    }
    
    /**
     * Read individual email message
     * 
     * @param string $messageId Message ID
     * @return array Message content or error
     */
    public function readMessage($messageId) {
        if (empty($_SESSION['temp_email'])) {
            return ['success' => false, 'error' => 'No email session found'];
        }
        
        $emailData = $_SESSION['temp_email'];
        
        if ($emailData['api_type'] === 'mailtm') {
            return $this->readMailTmMessage($messageId, $emailData['token']);
        } else {
            return $this->read1SecMailMessage($messageId, $emailData['username'], $emailData['domain']);
        }
    }
    
    /**
     * Delete email session and generate new one
     * 
     * @return array New email data
     */
    public function deleteAndRegenerate() {
        // Clear session
        unset($_SESSION['temp_email']);
        unset($_SESSION['created_at']);
        
        // Generate new email
        return $this->generateEmail();
    }
    
    /**
     * Get current email from session
     * 
     * @return array Email data or null
     */
    public function getCurrentEmail() {
        if (empty($_SESSION['temp_email'])) {
            return null;
        }
        
        return [
            'success' => true,
            'email' => $_SESSION['temp_email']['email'],
            'api_type' => $_SESSION['temp_email']['api_type'],
            'created_at' => $_SESSION['created_at'] ?? null
        ];
    }
}

// Handle API requests
header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$api = new TempMailAPI();

try {
    switch ($action) {
        case 'generate':
            $result = $api->generateEmail();
            break;
            
        case 'get_current':
            $result = $api->getCurrentEmail();
            if ($result === null) {
                $result = ['success' => false, 'error' => 'No email found'];
            }
            break;
            
        case 'get_messages':
            $result = $api->getMessages();
            break;
            
        case 'read_message':
            $messageId = $_GET['id'] ?? $_POST['id'] ?? '';
            if (empty($messageId)) {
                $result = ['success' => false, 'error' => 'Message ID required'];
            } else {
                $result = $api->readMessage($messageId);
            }
            break;
            
        case 'delete_regenerate':
            $result = $api->deleteAndRegenerate();
            break;
            
        default:
            $result = ['success' => false, 'error' => ERROR_INVALID_REQUEST];
            break;
    }
    
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred: ' . $e->getMessage()
    ]);
}
