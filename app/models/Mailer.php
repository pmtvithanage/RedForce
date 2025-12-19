<?php

namespace App\Models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Config\MailConfig;

class Mailer
{
    private $mailer;
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/mail.php';
        $this->mailer = new PHPMailer(true);
        
        $this->configure();
    }

    private function configure()
    {
        // Server settings
        if ($this->config['smtp']['enabled'] ?? false) {
            // SMTP configuration
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp']['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['smtp']['username'];
            $this->mailer->Password = $this->config['smtp']['password'];
            $this->mailer->SMTPSecure = $this->config['smtp']['encryption'];
            $this->mailer->Port = $this->config['smtp']['port'];
        } else {
            // Use mail() function
            $this->mailer->isMail();
        }

        // Default from address
        $this->mailer->setFrom(
            $this->config['smtp']['from_email'] ?? $this->config['sendmail']['from_email'],
            $this->config['smtp']['from_name'] ?? $this->config['sendmail']['from_name']
        );
        
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->isHTML(true);
    }

    public function sendEmail($to, $subject, $body, $attachments = [])
    {
        try {
            // Clear recipients from previous sends
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();

            // Add recipient
            if (is_array($to)) {
                foreach ($to as $email => $name) {
                    $this->mailer->addAddress($email, $name);
                }
            } else {
                $this->mailer->addAddress($to);
            }

            // Add attachments
            foreach ($attachments as $attachment) {
                if (is_array($attachment)) {
                    $this->mailer->addAttachment(
                        $attachment['path'],
                        $attachment['name'] ?? null
                    );
                } else {
                    $this->mailer->addAttachment($attachment);
                }
            }

            // Email content
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags($body); // Plain text version

            return $this->mailer->send();
            
        } catch (Exception $e) {
            // Log error (implement your logging mechanism)
            error_log("Mailer Error: " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    public function sendTemplateEmail($to, $template, $data = [], $attachments = [])
    {
        // Render email template
        $body = $this->renderTemplate($template, $data);
        $subject = $data['subject'] ?? 'No Subject';
        
        return $this->sendEmail($to, $subject, $body, $attachments);
    }

    private function renderTemplate($template, $data)
    {
        // Extract variables for the template
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the template file
        $templatePath = __DIR__ . '/../views/emails/' . $template . '.php';
        if (file_exists($templatePath)) {
            include $templatePath;
        } else {
            throw new Exception("Email template not found: $template");
        }
        
        // Get the contents and clean the buffer
        return ob_get_clean();
    }

    // Additional helper methods
    public function addCC($email, $name = '')
    {
        $this->mailer->addCC($email, $name);
    }

    public function addBCC($email, $name = '')
    {
        $this->mailer->addBCC($email, $name);
    }

    public function setReplyTo($email, $name = '')
    {
        $this->mailer->addReplyTo($email, $name);
    }
}