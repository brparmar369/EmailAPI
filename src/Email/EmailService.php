<?php

namespace Email;

/**
 * Class EmailService
 *
 * Service class that manages sending emails through different providers.
 * Uses failover mechanism to try multiple providers if the first one fails.
 *
 */
class EmailService
{
    /**
     * @var EmailServiceFailoverManager The failover manager responsible for handling multiple providers.
     */
    public EmailServiceFailoverManager $failoverManager;

    /**
     * Constructor for EmailService.
     *
     * @param EmailServiceFailoverManager $failoverManager The failover manager responsible for handling multiple providers.
     */
    public function __construct(EmailServiceFailoverManager $failoverManager)
    {
        $this->failoverManager = $failoverManager;
    }

    /**
     * Sends an email by utilizing the failover manager.
     *
     * @param string $recipient The email address of the recipient.
     * @param string $subject The subject of the email.
     * @param string $body The body content of the email.
     * @param array  $templateData An associative array containing dynamic data to populate the email template (optional).
     *
     * @return bool Returns `true` if an email is successfully sent, `false` otherwise.
     */
    public function sendEmailService(
        string $recipient,
        string $subject,
        string $body,
        array $templateData = []
    ) {
        return $this->failoverManager->sendEmailOverFailoverManager($recipient, $subject, $body, $templateData);
    }
}
