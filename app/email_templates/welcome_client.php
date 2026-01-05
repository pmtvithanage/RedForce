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
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f8f9fa;
      }
      
      /* Container */
      .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
      }
      
      /* Header */
      .header {
        background: linear-gradient(135deg, #a40000 0%, #cc0000 100%);
        color: white;
        text-align: center;
        padding: 40px 20px;
        position: relative;
      }
      
      .header::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 100%;
        height: 20px;
        background-color: white;
        border-radius: 50% 50% 0 0;
      }
      
      .logo {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
      }
      
      .welcome-icon {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
      }
      
      .greeting {
        font-size: 24px;
        margin: 0;
        font-weight: 600;
      }
      
      /* Content */
      .content {
        padding: 40px 30px;
      }
      
      .intro-text {
        font-size: 16px;
        color: #555;
        margin-bottom: 30px;
        text-align: center;
      }
      
      /* Info card */
      .info-card {
        background-color: #fff5f5;
        border-left: 4px solid #a40000;
        border-radius: 8px;
        padding: 20px;
        margin: 25px 0;
        box-shadow: 0 4px 6px rgba(164, 0, 0, 0.05);
      }
      
      .info-title {
        font-size: 18px;
        font-weight: 600;
        margin-top: 0;
        margin-bottom: 15px;
        color: #a40000;
      }
      
      .credential-item {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
      }
      
      .credential-label {
        font-weight: 600;
        min-width: 140px;
        color: #555;
      }
      
      .credential-value {
        background-color: #fff;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        flex-grow: 1;
      }
      
      /* Button */
      .button-container {
        text-align: center;
        margin: 35px 0 25px;
      }
      
      .login-button {
  display: inline-block;
  background: white;
  color: #a40000;
  text-decoration: none;
  padding: 14px 32px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 16px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
}

.login-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(164, 0, 0, 0.4);
  background-color: #fff5f5;
}
      
      /* Steps */
      .steps-container {
        margin-top: 40px;
      }
      
      .step {
        display: flex;
        margin-bottom: 25px;
        align-items: flex-start;
      }
      
      .step-number {
        color: #a40000;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 15px;
        flex-shrink: 0;
      }
      
      .step-content {
        flex-grow: 1;
      }
      
      .step-title {
        font-weight: 600;
        margin: 0 0 5px;
        color: #a40000;
      }
      
      .step-description {
        color: #666;
        margin: 0;
        font-size: 14.5px;
      }
      
      /* Features section */
      .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-top: 40px;
      }
      
      .feature {
        text-align: center;
        padding: 20px 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-top: 3px solid #a40000;
      }
      
      .feature-icon {
        font-size: 24px;
        color: #a40000;
        margin-bottom: 10px;
        display: block;
      }
      
      .feature-title {
        font-weight: 600;
        margin: 0 0 8px;
        color: #333;
        font-size: 15px;
      }
      
      .feature-desc {
        font-size: 13px;
        color: #666;
        margin: 0;
      }
      
      /* Footer */
      .footer {
        background-color: #f9f0f0;
        padding: 25px 30px;
        border-top: 1px solid #eaeaea;
        text-align: center;
        color: #666;
        font-size: 14px;
      }
      
      .support-note {
        background-color: #fff5f5;
        border: 1px solid #ffcccc;
        border-radius: 6px;
        padding: 12px 16px;
        margin-top: 15px;
        font-size: 13px;
        color: #a40000;
      }
      
      .company-name {
        font-weight: 600;
        color: #a40000;
      }
      
      .contact-info {
        margin-top: 15px;
        font-size: 13px;
        color: #777;
      }
      
      /* Responsive adjustments */
      @media (max-width: 600px) {
        .content {
          padding: 30px 20px;
        }
        
        .header {
          padding: 30px 15px;
        }
        
        .greeting {
          font-size: 22px;
        }
        
        .credential-item {
          flex-direction: column;
          align-items: flex-start;
        }
        
        .credential-label {
          margin-bottom: 5px;
          min-width: auto;
        }
        
        .features {
          grid-template-columns: 1fr;
        }
      }
      
      /* Animation */
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
      }
      
      .fade-in {
        animation: fadeIn 0.5s ease-out;
      }
    </style>
  </head>
  <body>
    <div class="container fade-in">
      <!-- Header section -->
      <div class="header">
        <div class="logo"><?= htmlspecialchars($site_name ?? SITE_NAME) ?></div>
        <h1 class="greeting">Welcome, <?= htmlspecialchars($client_name ?? 'Client') ?>!</h1>
      </div>
      
      <!-- Main content -->
      <div class="content">
        <p class="intro-text">We're thrilled to welcome you to <?= htmlspecialchars($site_name ?? SITE_NAME) ?>. Your account is now active and ready to use.</p>
        
        <!-- Credentials card -->
        <div class="info-card">
          <h3 class="info-title">Your Account Credentials</h3>
          
          <div class="credential-item">
            <span class="credential-label">Login ID:</span>
            <span class="credential-value"><?= htmlspecialchars($login_id) ?></span>
          </div>
          
          <div class="credential-item">
            <span class="credential-label">Temporary Password:</span>
            <span class="credential-value"><?= htmlspecialchars($temp_password) ?></span>
          </div>
          
          <div style="margin-top: 20px; padding: 12px; background-color: #fff; border-radius: 6px; border-left: 3px solid #a40000;">
            <p style="margin: 0; font-size: 14px; color: #a40000; font-weight: 600;">
              🔒 For security, please change your password after first login
            </p>
          </div>
        </div>
        
        <!-- Call to action button -->
        <div class="button-container">
          <a href="<?= htmlspecialchars($login_url) ?>" class="login-button">Login to Your Account</a>
          <p style="margin-top: 10px; font-size: 14px; color: #666;">
            Or visit: <strong><?= htmlspecialchars($login_url) ?></strong>
          </p>
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
      </div>
      
      <!-- Footer -->
      <div class="footer">
        <p>Best regards,<br><span class="company-name">The <?= htmlspecialchars($site_name ?? SITE_NAME) ?> Team</span></p>
        
        <div class="contact-info">
          Need assistance? We're here to help you get started.
        </div>
        
        <div class="support-note">
          <strong>Important Security Notice:</strong> If you did not create this account, please contact our support team immediately.
        </div>
        
        <p style="margin-top: 20px; font-size: 13px; color: #888; border-top: 1px solid #eee; padding-top: 15px;">
          This is an automated message. Please do not reply to this email.<br>
          © <?= date('Y') ?> <?= htmlspecialchars($site_name ?? SITE_NAME) ?>. All rights reserved.
        </p>
      </div>
    </div>
  </body>
</html>