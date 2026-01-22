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
        background: linear-gradient(135deg, #a40000 0%, #cc0000 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
      }
      
      /* Button */
      .button-container {
        text-align: center;
        margin: 35px 0 30px;
      }
      
      .login-button {
        display: inline-block;
        background: linear-gradient(135deg, #b7b7b7 0%, #dbdbdb 100%);
        color: white;
        text-decoration: none;
        padding: 15px 35px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(147, 147, 147, 0.3);
      }

      .login-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(111, 111, 111, 0.4);
        background: linear-gradient(135deg, #a1a1a1 0%, #959595 100%);
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
      
      /* Steps */
      .steps-container {
        margin: 25px 0;
      }
      
      .step {
        display: flex;
        margin-bottom: 25px;
        align-items: flex-start;
      }
      
      .step-number {
        color: black;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 15px;
        flex-shrink: 0;
        font-size: 14px;
      }
      
      .step-content {
        flex-grow: 1;
      }
      
      .step-title {
        font-weight: 600;
        margin: 0 0 5px;
        color: #a40000;
        font-size: 16px;
      }
      
      .step-description {
        color: #4a5568;
        margin: 0;
        font-size: 14.5px;
      }
      
      /* Features section */
      .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin: 25px 0;
      }
      
      .feature {
        text-align: center;
        padding: 20px 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-top: 3px solid #a40000;
      }
      
      .feature-title {
        font-weight: 600;
        margin: 0 0 8px;
        color: #2d3748;
        font-size: 15px;
      }
      
      .feature-desc {
        font-size: 13px;
        color: #666;
        margin: 0;
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
        
        .features {
          grid-template-columns: 1fr;
        }
        
        .step {
          flex-direction: column;
        }
        
        .step-number {
          margin-right: 0;
          margin-bottom: 10px;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <!-- Header -->
      <div class="header">
        <span class="logo"><?= htmlspecialchars($site_name ?? SITE_NAME) ?></span>
        <h1>Welcome, <?= htmlspecialchars($client_name ?? 'Client') ?>!</h1>
        <div class="badge">Account Created Successfully</div>
      </div>
      
      <!-- Greeting -->
      <p class="greeting">Dear <?= htmlspecialchars($client_name ?? 'Client') ?>,</p>
      
      <p class="greeting">Welcome to <strong style="color: #a40000;"><?= htmlspecialchars($site_name ?? SITE_NAME) ?></strong>. Your client account has been successfully created and is ready to use with the following credentials.</p>
      
      <!-- Account Details Card -->
      <div class="info-card">
        <h3>Account Credentials</h3>
        
        <div class="detail-row">
          <span class="detail-label">Login ID:</span>
          <span class="detail-value"><?= htmlspecialchars($login_id) ?></span>
        </div>
        
        <div class="detail-row">
          <span class="detail-label">Temporary Password:</span>
          <span class="detail-value"><?= htmlspecialchars($temp_password) ?></span>
        </div>
      </div>
      
      <!-- Instructions -->
      <div class="instructions">
        <h4>Important Security Instructions</h4>
        <ul>
          <li>This is a temporary password - change it immediately after first login</li>
          <li>Keep your credentials confidential and do not share with anyone</li>
          <li>Log out after each session, especially on shared computers</li>
          <li>Report any suspicious activity immediately to our support team</li>
        </ul>
      </div>
      
      <!-- Steps to follow -->
      <div class="steps-container">
        <h3 style="color: #a40000; text-align: center; margin-bottom: 25px;">Getting Started</h3>
        
        <div class="step">
          <div class="step-number">1. </div>
          <div class="step-content">
            <h4 class="step-title">Access Your Account</h4>
            <p class="step-description">Use your credentials above to log into your dashboard</p>
          </div>
        </div>
        
        <div class="step">
          <div class="step-number">2. </div>
          <div class="step-content">
            <h4 class="step-title">Update Your Password</h4>
            <p class="step-description">Navigate to Account Settings to create a secure password</p>
          </div>
        </div>
        
        <div class="step">
          <div class="step-number">3. </div>
          <div class="step-content">
            <h4 class="step-title">Complete Your Profile</h4>
            <p class="step-description">Add your contact details and preferences for a personalized experience</p>
          </div>
        </div>
      </div>
      
      <!-- Features section -->
      <div class="features">
        <div class="feature">
          <h4 class="feature-title">Fast Dashboard</h4>
          <p class="feature-desc">Quick access to all your tools and data</p>
        </div>
        
        <div class="feature">
          <h4 class="feature-title">Secure Account</h4>
          <p class="feature-desc">Enterprise-grade security for your data</p>
        </div>
        
        <div class="feature">
          <h4 class="feature-title">Mobile Ready</h4>
          <p class="feature-desc">Access your account from any device</p>
        </div>
        
        <div class="feature">
          <h4 class="feature-title">24/7 Support</h4>
          <p class="feature-desc">Our team is always here to help you</p>
        </div>
      </div>
      
      <!-- Additional Information -->
      <div class="additional-info">
        <h4>Next Steps</h4>
        <p>After logging in for the first time, please:</p>
        <p>1. Change your temporary password immediately</p>
        <p>2. Complete your client profile in the dashboard</p>
        <p>3. Review your account settings and preferences</p>
        <p>4. Familiarize yourself with the platform features</p>
      </div>
      
      <!-- Login Button -->
      <div class="button-container">
        <a href="<?= htmlspecialchars($login_url) ?>" class="login-button">Access Your Account</a>
        <span class="login-url">Portal URL: <?= htmlspecialchars($login_url) ?></span>
      </div>
      
      <!-- Closing -->
      <p class="greeting">Use your credentials to access your client dashboard where you can manage your account, view services, and utilize all available features effectively.</p>
      
      <!-- Footer -->
      <div class="footer">
        <p class="signature">Best regards,<br>The <?= htmlspecialchars($site_name ?? SITE_NAME) ?> Team</p>
        
        <div class="support-note">
          <strong>Security Notice:</strong> If you did not expect this email or believe it was sent in error, please contact our support team immediately.
        </div>
        
        <p style="margin-top: 20px; font-size: 13px; color: #a0aec0;">
          This is an automated system message. Please do not reply to this email.<br>
          © <?= date('Y') ?> <?= htmlspecialchars($site_name ?? SITE_NAME) ?>. All rights reserved.
        </p>
      </div>
    </div>
  </body>
</html>