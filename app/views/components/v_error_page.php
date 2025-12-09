<?php
// Set error title based on error code
$errorTitles = [
    400 => 'Bad Request',
    403 => 'Access Denied',
    404 => 'Page Not Found',
    500 => 'Internal Server Error',
    503 => 'Service Unavailable'
];
$errorTitle = isset($errorTitles[$data['error_code']]) ? $errorTitles[$data['error_code']] : 'An Error Occurred';

// Determine error message
$userMessage = "We're sorry, but something went wrong. Please try again later.";
$debugMessage = $data['error_message'];

// Hide technical details in production
if (!defined('DEBUG_MODE') || !DEBUG_MODE) {
    $debugMessage = "Error reference: " . md5($data['timestamp'] . $data['error_file']);
    $data['error_file'] = '';
    $data['error_line'] = '';
    $data['error_trace'] = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['error_code']; ?> - <?php echo $errorTitle; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }
        
        .error-container {
            max-width: 800px;
            width: 100%;
            text-align: center;
            padding: 40px;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 2;
            position: relative;
        }
        
        .error-header {
            margin-bottom: 30px;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(45deg, #ff8a00, #e52e71, #9d50bb);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .error-code::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 5px;
            bottom: -10px;
            left: 0;
            background: linear-gradient(90deg, transparent, #ff8a00, transparent);
            border-radius: 5px;
        }
        
        .error-title {
            font-size: 2.5rem;
            margin: 20px 0 10px;
            color: #fff;
        }
        
        .error-message {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 30px;
            color: #ddd;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .error-illustration {
            margin: 30px 0;
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.2);
        }
        
        .error-details {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            text-align: left;
            font-family: monospace;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            font-size: 0.9rem;
        }
        
        .error-details pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 14px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #ff8a00, #e52e71);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(229, 46, 113, 0.3);
        }
        
        .btn-secondary {
            background: transparent;
            color: #ddd;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .toggle-details {
            margin-top: 20px;
            color: #aaa;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
        }
        
        .toggle-details:hover {
            color: #fff;
        }
        
        .reference-id {
            margin-top: 20px;
            color: #888;
            font-size: 0.8rem;
            font-family: monospace;
        }
        
        /* Background animation */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }
        
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            animation: float 15s infinite linear;
        }
        
        @keyframes float {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
            
            .error-message {
                font-size: 1.1rem;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
        
        @media (max-width: 480px) {
            .error-code {
                font-size: 5rem;
            }
            
            .error-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Background animation -->
    <div class="bg-animation" id="bgAnimation"></div>
    
    <div class="error-container">
        <div class="error-header">
            <h1 class="error-code"><?php echo $data['error_code']; ?></h1>
            <h2 class="error-title"><?php echo $errorTitle; ?></h2>
        </div>
        
        <div class="error-illustration">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <p class="error-message">
            <?php echo $userMessage; ?>
        </p>
        
        <div class="error-details" id="errorDetails">
            <h3>Error Details:</h3>
            <p><strong>Message:</strong> <?php echo $debugMessage; ?></p>
            <?php if($data['error_file']): ?>
                <p><strong>File:</strong> <?php echo $data['error_file']; ?></p>
                <p><strong>Line:</strong> <?php echo $data['error_line']; ?></p>
            <?php endif; ?>
            
            <?php if($data['request_url']): ?>
                <p><strong>Request URL:</strong> <?php echo $data['request_url']; ?></p>
            <?php endif; ?>
            
            <?php if($data['request_method']): ?>
                <p><strong>Request Method:</strong> <?php echo $data['request_method']; ?></p>
            <?php endif; ?>
            
            <?php if($data['error_trace']): ?>
                <p><strong>Stack Trace:</strong></p>
                <pre><?php echo $data['error_trace']; ?></pre>
            <?php endif; ?>
        </div>
        
        <?php if(defined('DEBUG_MODE') && DEBUG_MODE): ?>
            <div class="toggle-details" id="toggleDetails">
                <i class="fas fa-chevron-down"></i> <span>Show Technical Details</span>
            </div>
        <?php endif; ?>
        
        <div class="error-actions">
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home"></i> Go to Homepage
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
            <button class="btn btn-secondary" id="contactSupport">
                <i class="fas fa-headset"></i> Contact Support
            </button>
        </div>
        
        <div class="reference-id">
            Reference ID: <?php echo md5($data['timestamp'] . $data['error_file']); ?>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle error details visibility
            const toggleBtn = document.getElementById('toggleDetails');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const details = document.getElementById('errorDetails');
                    const icon = this.querySelector('i');
                    const text = this.querySelector('span');
                    
                    if (details.style.display === 'block') {
                        details.style.display = 'none';
                        icon.className = 'fas fa-chevron-down';
                        text.textContent = 'Show Technical Details';
                    } else {
                        details.style.display = 'block';
                        icon.className = 'fas fa-chevron-up';
                        text.textContent = 'Hide Technical Details';
                    }
                });
            }
            
            // Contact support button
            document.getElementById('contactSupport').addEventListener('click', function() {
                alert('For support, please email: support@redforce.com\n\nError Reference: <?php echo md5($data["timestamp"] . $data["error_file"]); ?>');
            });
            
            // Create background animation
            createBackgroundAnimation();
        });
        
        // Create background animation circles
        function createBackgroundAnimation() {
            const bgAnimation = document.getElementById('bgAnimation');
            const colors = [
                'rgba(255, 138, 0, 0.05)',
                'rgba(229, 46, 113, 0.05)',
                'rgba(157, 80, 187, 0.05)',
                'rgba(0, 150, 255, 0.05)'
            ];
            
            for (let i = 0; i < 15; i++) {
                const circle = document.createElement('div');
                circle.className = 'bg-circle';
                
                // Random properties
                const size = Math.random() * 300 + 50;
                const color = colors[Math.floor(Math.random() * colors.length)];
                const left = Math.random() * 100;
                const top = Math.random() * 100;
                const delay = Math.random() * 10;
                const duration = Math.random() * 20 + 10;
                
                // Apply styles
                circle.style.width = `${size}px`;
                circle.style.height = `${size}px`;
                circle.style.background = color;
                circle.style.left = `${left}%`;
                circle.style.top = `${top}%`;
                circle.style.animationDelay = `${delay}s`;
                circle.style.animationDuration = `${duration}s`;
                
                bgAnimation.appendChild(circle);
            }
        }
    </script>
</body>
</html>