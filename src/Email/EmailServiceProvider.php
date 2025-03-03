<?php

namespace Email;

/**
 * Interface EmailServiceProvider
 * 
 * This interface defines the contract that all email service providers must follow.
 * It provides a method `sendEmail` that all email providers need to implement to send emails.
 */
interface EmailServiceProvider
{
    /**
     * Sends an email to the specified recipient.
     * 
     * @param string $recipient The email address of the recipient.
     * @param string $subject The subject of the email.
     * @param string $body The body content of the email.
     * @param array $templateData An associative array containing dynamic data to populate the email template (optional).
     * 
     * @return bool Returns `true` if the email was sent successfully, `false` otherwise.
     */
    public function sendEmail(
        string $recipient, 
        string $subject, 
        string $body, 
        array $templateData = []
    );
}
