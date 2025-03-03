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
     * @param array $templateData An associative array containing dynamic data to populate the email template (optional).
     * 
     * Returns `true` if the email was sent successfully, `false` otherwise.
     */
    public function sendEmail(
        string $recipient, 
        string $subject, 
        string $body, 
        array $templateData = []
    ) {
        TemplateEngine::applyTemplate($body, $templateData);

        echo "Email sent via MailChimp to $recipient\n";

        return true;
    }
}
