<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Welcome to <?= htmlspecialchars($site_name ?? SITE_NAME) ?></title>
    <style>
      /* Reset and base styles */
      body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #2c3e50;
        background-color: #f8fafc;
      }
      
      /* Container */
      .container {
        max-width: 600px;
        margin: 30px auto;
        background-color: #ffffff;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
      }
      
      /* Header */
      .header {
        text-align: center;
        margin-bottom: 35px;
        padding-bottom: 25px;
        border-bottom: 2px solid #e9ecef;
      }
      
      .header h1 {
        color: #a40000;
        font-size: 28px;
        margin: 0 0 10px 0;
        font-weight: 700;
      }
      
      .header .logo {
        font-size: 20px;
        color: #a40000;
        font-weight: 600;
        margin-bottom: 15px;
        display: block;
      }
      
      .badge {
        display: inline-block;
        background: linear-gradient(135deg, #a40000 0%, #cc0000 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-top: 10px;
      }
      
      /* Content */
      .greeting {
        font-size: 18px;
        color: #2d3748;
        margin-bottom: 25px;
        line-height: 1.5;
      }
      
      /* Info Card */
      .info-card {
        background: linear-gradient(to right, #fff5f5, #ffeaea);
        border-left: 4px solid #a40000;
        border-radius: 8px;
        padding: 25px;
        margin: 25px 0;
        box-shadow: 0 4px 6px rgba(164, 0, 0, 0.08);
      }
      
      .info-card h3 {
        color: #a40000;
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 18px;
        font-weight: 600;
      }
      
      .detail-row {
        display: flex;
        margin-bottom: 15px;
        align-items: center;
      }
      
      .detail-label {
        font-weight: 600;
        color: #4a5568;
        min-width: 160px;
        font-size: 15px;
      }
      
      .detail-value {
        background-color: white;
        padding: 10px 15px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        flex-grow: 1;
        font-family: 'Courier New', monospace;
        color: #2d3748;
        font-size: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
      }
      
      .role-badge {
        display: inline-block;
        background: linear-gradient(135deg, #8B0000 0%, #a40000 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
      }
      
      /* Button */
      .button-container {
        text-align: center;
        margin: 35px 0 30px;
      }
      
      .login-button {
        display: inline-block;
        background: linear-gradient(135deg, #a40000 0%, #cc0000 100%);
        color: white;
        text-decoration: none;
        padding: 15px 35px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
      }

      .login-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(164, 0, 0, 0.4);
        background: linear-gradient(135deg, #8b0000 0%, #a40000 100%);
      }
      
      .login-url {
        display: block;
        margin-top: 15px;
        font-size: 14px;
        color: #a40000;
        font-weight: 500;
        word-break: break-all;
      }
      
      /* Instructions */
      .instructions {
        background-color: #fff5f5;
        border: 1px solid #ffcccc;
        border-radius: 8px;
        padding: 20px;
        margin: 25px 0;
      }
      
      .instructions h4 {
        color: #a40000;
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 16px;
        display: flex;
        align-items: center;
      }
      
      .instructions h4:before {
        content: "⚠️";
        margin-right: 10px;
      }
      
      .instructions ul {
        margin: 0;
        padding-left: 20px;
        color: #8b0000;
      }
      
      .instructions li {
        margin-bottom: 8px;
        line-height: 1.5;
      }
      
      /* Admin privileges box */
      .privileges-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #a40000;
        border-radius: 8px;
        padding: 20px;
        margin: 25px 0;
      }
      
      .privileges-box h4 {
        color: #a40000;
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 16px;
        font-weight: 700;
      }
      
      .privilege-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        color: #2d3748;
      }
      
      .privilege-item:before {
        content: "✓";
        color: #a40000;
        font-weight: bold;
        margin-right: 10px;
        font-size: 18px;
      }
      
      /* Additional Info */
      .additional-info {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin: 25px 0;
      }
      
      .additional-info h4 {
        color: #a40000;
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 16px;
      }
      
      .additional-info p {
        margin: 10px 0;
        color: #4a5568;
        font-size: 15px;
      }
      
      /* Footer */
      .footer {
        margin-top: 40px;
        padding-top: 25px;
        border-top: 1px solid #e2e8f0;
        font-size: 14px;
        color: #718096;
        text-align: center;
      }
      
      .support-note {
        background-color: #fff5f5;
        border: 1px solid #ffcccc;
        border-radius: 6px;
        padding: 15px;
        margin-top: 20px;
        font-size: 13px;
        color: #a40000;
      }
      
      .signature {
        font-weight: 600;
        color: #a40000;
        margin: 20px 0;
      }
      
      /* Responsive */
      @media (max-width: 640px) {
        .container {
          margin: 15px;
          padding: 25px;
        }
        
        .detail-row {
          flex-direction: column;
          align-items: flex-start;
        }
        
        .detail-label {
          margin-bottom: 8px;
          min-width: auto;
        }
        
        .detail-value {
          width: 100%;
        }
        
        .header h1 {
          font-size: 24px;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <!-- Header -->
      <div class="header">
        <span class="logo"><?= htmlspecialchars($site_name ?? SITE_NAME) ?></span>
        <h1>Administrator Access Granted</h1>
        <div class="badge">System Administrator</div>
      </div>
      
      <!-- Greeting -->
      <p class="greeting">Dear <?= htmlspecialchars($admin_name ?? 'Administrator') ?>,</p>
      
      <p class="greeting">Welcome to <strong style="color: #a40000;"><?= htmlspecialchars($site_name ?? SITE_NAME) ?></strong>. Your administrator account has been successfully created with full system privileges and administrative capabilities.</p>
      
      <!-- Account Details Card -->
      <div class="info-card">
        <h3>Administrator Account Details</h3>
        
        <div class="detail-row">
          <span class="detail-label">Admin ID:</span>
          <span class="detail-value"><?= htmlspecialchars($login_id) ?></span>
        </div>
        
        <div class="detail-row">
          <span class="detail-label">Temporary Password:</span>
          <span class="detail-value"><?= htmlspecialchars($temp_password) ?></span>
        </div>
        
        <div class="detail-row">
          <span class="detail-label">Access Level:</span>
          <div class="detail-value">
            <span class="role-badge">Administrator</span>
          </div>
        </div>
      </div>
      
      <!-- Instructions -->
      <div class="instructions">
        <h4>Critical Security Instructions</h4>
        <ul>
          <li><strong>IMMEDIATE ACTION REQUIRED:</strong> Change your temporary password on first login</li>
          <li>Administrator credentials grant full system access - keep them absolutely confidential</li>
          <li>Never share your admin credentials with anyone, including other staff members</li>
          <li>Log out after each session, especially on shared or public computers</li>
          <li>Report any suspicious activity or unauthorized access attempts immediately</li>
          <li>Enable two-factor authentication if available for enhanced security</li>
        </ul>
      </div>
      
      <!-- Admin Privileges -->
      <div class="privileges-box">
        <h4>Administrator Privileges</h4>
        <div class="privilege-item">Full system administration and configuration</div>
        <div class="privilege-item">User account management and access control</div>
        <div class="privilege-item">Officer and client management capabilities</div>
        <div class="privilege-item">Route and site assignment authority</div>
        <div class="privilege-item">Leave request approval and rejection</div>
        <div class="privilege-item">System reports and analytics access</div>
        <div class="privilege-item">Complete audit trail and activity monitoring</div>
      </div>
      
      <!-- Additional Information -->
      <div class="additional-info">
        <h4>First Login Checklist</h4>
        <p>After logging in for the first time, please:</p>
        <p>1. <strong>Change your temporary password immediately</strong> to a strong, unique password</p>
        <p>2. Complete your administrator profile with accurate contact information</p>
        <p>3. Review all pending administrative tasks and notifications</p>
        <p>4. Familiarize yourself with the admin dashboard and available features</p>
        <p>5. Review system security settings and user access permissions</p>
        <p>6. Set up notification preferences for critical system alerts</p>
      </div>
      
      <!-- Login Button -->
      <div class="button-container">
        <a href="<?= htmlspecialchars($login_url) ?>" class="login-button">Access Admin Portal</a>
        <span class="login-url">Portal URL: <?= htmlspecialchars($login_url) ?></span>
      </div>
      
      <!-- Closing -->
      <p class="greeting">As a system administrator, you have significant responsibility for maintaining the security and integrity of the platform. Please exercise your administrative privileges with care and in accordance with organizational policies.</p>
      
      <!-- Footer -->
      <div class="footer">
        <p class="signature">Best regards,<br><?= htmlspecialchars($site_name ?? SITE_NAME) ?> Management</p>
        
        <div class="support-note">
          <strong>Security Notice:</strong> If you did not expect this email or believe it was sent in error, please contact the system administrator or IT security team immediately. This account grants full administrative access to the system.
        </div>
        
        <p style="margin-top: 20px; font-size: 13px; color: #a0aec0;">
          This is an automated system message. Please do not reply to this email.<br>
          © <?= date('Y') ?> <?= htmlspecialchars($site_name ?? SITE_NAME) ?>. All rights reserved.
        </p>
      </div>
    </div>
  </body>
</html>
