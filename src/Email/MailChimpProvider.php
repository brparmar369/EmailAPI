<?php

namespace Email;

/**
 * Class MailChimpProvider
 * 
 * Implements the EmailServiceProvider interface using the MailChimp API for send Email.
 */
class MailChimpProvider implements EmailServiceProvider
{
    /**
     * Sends an email using the MailChimp service.
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

        echo "Email sent via MailChimp to $recipient\n";

        return true;
    }
}
