<?php
/**
 * Simple Test File to Verify Installation
 * 
 * Access this file at: http://localhost/temp-mail/test.php
 * It will check if your environment is properly configured
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temp Mail - Installation Test</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #6366f1;
            padding-bottom: 10px;
        }
        .test-item {
            margin: 20px 0;
            padding: 15px;
            border-left: 4px solid #ccc;
            background: #f9f9f9;
        }
        .success {
            border-left-color: #10b981;
            background: #f0fdf4;
        }
        .error {
            border-left-color: #ef4444;
            background: #fef2f2;
        }
        .warning {
            border-left-color: #f59e0b;
            background: #fffbeb;
        }
        .status {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .success .status { color: #10b981; }
        .error .status { color: #ef4444; }
        .warning .status { color: #f59e0b; }
        code {
            background: #e5e7eb;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 14px;
        }
        .info {
            background: #eff6ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #6366f1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .button:hover {
            background: #4f46e5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Temp Mail Installation Test</h1>
        
        <div class="info">
            <strong>ℹ️ About This Test:</strong><br>
            This page checks if your server environment is properly configured to run the Temp Mail application.
        </div>

        <?php
        $allPassed = true;
        
        // Test 1: PHP Version
        echo '<div class="test-item ' . (version_compare(PHP_VERSION, '7.4.0', '>=') ? 'success' : 'error') . '">';
        echo '<div class="status">' . (version_compare(PHP_VERSION, '7.4.0', '>=') ? '✅' : '❌') . ' PHP Version</div>';
        echo '<strong>Current:</strong> PHP ' . PHP_VERSION . '<br>';
        echo '<strong>Required:</strong> PHP 7.4.0 or higher<br>';
        if (version_compare(PHP_VERSION, '7.4.0', '<')) {
            echo '<strong>Action:</strong> Update your PHP version';
            $allPassed = false;
        }
        echo '</div>';
        
        // Test 2: cURL Extension
        $curlEnabled = extension_loaded('curl');
        echo '<div class="test-item ' . ($curlEnabled ? 'success' : 'error') . '">';
        echo '<div class="status">' . ($curlEnabled ? '✅' : '❌') . ' cURL Extension</div>';
        echo '<strong>Status:</strong> ' . ($curlEnabled ? 'Enabled' : 'Disabled') . '<br>';
        if (!$curlEnabled) {
            echo '<strong>Action:</strong> Enable cURL extension in php.ini<br>';
            echo '<strong>How:</strong> Uncomment <code>extension=curl</code> in php.ini and restart Apache';
            $allPassed = false;
        }
        echo '</div>';
        
        // Test 3: JSON Extension
        $jsonEnabled = extension_loaded('json');
        echo '<div class="test-item ' . ($jsonEnabled ? 'success' : 'error') . '">';
        echo '<div class="status">' . ($jsonEnabled ? '✅' : '❌') . ' JSON Extension</div>';
        echo '<strong>Status:</strong> ' . ($jsonEnabled ? 'Enabled' : 'Disabled') . '<br>';
        if (!$jsonEnabled) {
            echo '<strong>Action:</strong> Enable JSON extension (usually enabled by default)';
            $allPassed = false;
        }
        echo '</div>';
        
        // Test 4: Session Support
        $sessionEnabled = function_exists('session_start');
        echo '<div class="test-item ' . ($sessionEnabled ? 'success' : 'error') . '">';
        echo '<div class="status">' . ($sessionEnabled ? '✅' : '❌') . ' Session Support</div>';
        echo '<strong>Status:</strong> ' . ($sessionEnabled ? 'Enabled' : 'Disabled') . '<br>';
        if (!$sessionEnabled) {
            echo '<strong>Action:</strong> Enable session support in PHP';
            $allPassed = false;
        }
        echo '</div>';
        
        // Test 5: File Permissions
        $configExists = file_exists('config.php');
        $configReadable = is_readable('config.php');
        echo '<div class="test-item ' . ($configExists && $configReadable ? 'success' : 'error') . '">';
        echo '<div class="status">' . ($configExists && $configReadable ? '✅' : '❌') . ' File Permissions</div>';
        echo '<strong>config.php exists:</strong> ' . ($configExists ? 'Yes' : 'No') . '<br>';
        echo '<strong>config.php readable:</strong> ' . ($configReadable ? 'Yes' : 'No') . '<br>';
        if (!$configExists || !$configReadable) {
            echo '<strong>Action:</strong> Check file permissions and location';
            $allPassed = false;
        }
        echo '</div>';
        
        // Test 6: Internet Connectivity (test API)
        if ($curlEnabled) {
            $ch = curl_init('https://www.1secmail.com/api/v1/?action=getDomainList');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            $apiAccessible = ($httpCode === 200 && !empty($response));
            
            echo '<div class="test-item ' . ($apiAccessible ? 'success' : 'warning') . '">';
            echo '<div class="status">' . ($apiAccessible ? '✅' : '⚠️') . ' API Connectivity</div>';
            echo '<strong>Test API:</strong> 1secmail<br>';
            echo '<strong>Status:</strong> ' . ($apiAccessible ? 'Connected' : 'Connection issue') . '<br>';
            if (!$apiAccessible) {
                echo '<strong>HTTP Code:</strong> ' . $httpCode . '<br>';
                if ($error) {
                    echo '<strong>Error:</strong> ' . htmlspecialchars($error) . '<br>';
                }
                echo '<strong>Note:</strong> This might be a temporary issue. Try again or check your internet connection.';
            }
            echo '</div>';
        }
        
        // Test 7: Required Files
        $requiredFiles = ['index.php', 'api-handler.php', 'config.php', 'assets/js/app.js', 'assets/css/style.css'];
        $missingFiles = [];
        foreach ($requiredFiles as $file) {
            if (!file_exists($file)) {
                $missingFiles[] = $file;
            }
        }
        
        echo '<div class="test-item ' . (empty($missingFiles) ? 'success' : 'error') . '">';
        echo '<div class="status">' . (empty($missingFiles) ? '✅' : '❌') . ' Required Files</div>';
        if (empty($missingFiles)) {
            echo '<strong>Status:</strong> All required files found<br>';
            echo '<strong>Files checked:</strong> ' . count($requiredFiles);
        } else {
            echo '<strong>Missing files:</strong><br>';
            foreach ($missingFiles as $file) {
                echo '- <code>' . htmlspecialchars($file) . '</code><br>';
            }
            $allPassed = false;
        }
        echo '</div>';
        
        // Final Summary
        echo '<div class="test-item ' . ($allPassed ? 'success' : 'error') . '">';
        echo '<div class="status">' . ($allPassed ? '🎉' : '❌') . ' Overall Status</div>';
        if ($allPassed) {
            echo '<strong>Result:</strong> Your environment is properly configured!<br>';
            echo '<strong>Next Step:</strong> You can now use the Temp Mail application.';
            echo '<br><br><a href="index.php" class="button">Launch Temp Mail →</a>';
        } else {
            echo '<strong>Result:</strong> Some requirements are not met.<br>';
            echo '<strong>Action:</strong> Please fix the issues marked with ❌ above and refresh this page.';
        }
        echo '</div>';
        ?>
        
        <div class="info" style="margin-top: 30px;">
            <strong>📚 Additional Information:</strong><br>
            <strong>PHP ini location:</strong> <code><?php echo php_ini_loaded_file(); ?></code><br>
            <strong>Server Software:</strong> <code><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></code><br>
            <strong>Document Root:</strong> <code><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></code><br>
            <strong>Current Directory:</strong> <code><?php echo __DIR__; ?></code>
        </div>
        
        <div style="text-align: center; margin-top: 30px; color: #666; font-size: 14px;">
            <p>Need help? Check the <a href="README.md" style="color: #6366f1;">README.md</a> or <a href="QUICKSTART.md" style="color: #6366f1;">QUICKSTART.md</a></p>
        </div>
    </div>
</body>
</html>
