<?php

namespace Email;

/**
 * Class SMTPProvider
 * 
 * Implements the EmailServiceProvider interface using SMTP for send Email.
 */
class SMTPProvider implements EmailServiceProvider
{
    /**
     * Sends an email using an SMTP service.
     * 
     * @param string $recipient The email address of the recipient.
     * @param string $subject The subject of the email.
     * @param string $body The body content of the email.
     * @param array $templateData An associative array of dynamic data for template variables (optional).
     * 
     * Returns 'true' if the email was sent successfully, 'false' otherwise.
     */
    public function sendEmail(
        string $recipient, 
        string $subject, 
        string $body, 
        array $templateData = []
    ) {
        // Validate the recipient email
        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) 
        {
            return false;
        }
        
        TemplateEngine::applyTemplate($body, $templateData);

        echo "Email sent via SMTP to $recipient\n";

        return true;
    }
}
