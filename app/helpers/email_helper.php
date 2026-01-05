<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Render an email template from app/email_templates/{name}.php
 * Template receives variables from $vars and should echo HTML content.
 * Returns rendered HTML string or false if template missing.
 */
function render_email_template($templateName, $vars = []) {
    $templatePath = APP_ROOT . '/email_templates/' . $templateName . '.php';
    if (!file_exists($templatePath)) {
        return false;
    }

    // Make variables available to template safely (skip collisions)
    extract($vars, EXTR_SKIP);

    ob_start();
    include $templatePath;
    return ob_get_clean();
}

/**
 * Send an email using a named template.
 * @param string $to
 * @param string $templateName
 * @param array $vars
 * @param string|null $subject
 * @param array $attachments
 * @return array send_email response
 */
function send_templated_email($to, $templateName, $vars = [], $subject = null, $attachments = []) {
    $body = render_email_template($templateName, $vars);
    if ($body === false) {
        return ['success' => false, 'message' => 'Template not found: ' . $templateName];
    }

    $subject = $subject ?? ($vars['subject'] ?? SITE_NAME . ' Notification');
    $altBody = strip_tags($body);
    return send_email($to, $subject, $body, $altBody, $attachments);
}

function send_email($to, $subject, $body, $altBody = '', $attachments = []) {
    $response = ['success' => false, 'message' => ''];

    try {
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;

        // Recipients
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $altBody ?: strip_tags($body);

        // Attachments
        foreach ($attachments as $filePath) {
            if (file_exists($filePath)) {
                $mail->addAttachment($filePath);
            }
        }

        $mail->send();
        $response['success'] = true;
        $response['message'] = 'Message sent';
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = $mail->ErrorInfo ?? $e->getMessage();
    }

    return $response;
}
