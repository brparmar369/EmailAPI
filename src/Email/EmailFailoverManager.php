<?php

namespace Email;

/**
 * Class EmailFailoverManager
 * Manages the failover logic for sending emails using multiple service providers.
 * If one provider fails, it will attempt to send using another provider, up to a configured failover limit.
 */
class EmailFailoverManager
{
    /**
     * @var array $providers List of email service providers.
     */
    public array $providers;

    /**
     * @var int $failoverLimit Maximum number of failovers before aborting.
     */
    public int $failoverLimit;

    /**
     * Constructor for EmailFailoverManager.
     * @param array $providers Array of EmailServiceProvider objects that will be used to send emails.
     * @param int $failoverLimit The number of failovers before aborting the operation (default is 3).
     */
    public function __construct(array $providers, int $failoverLimit = 3)
    {
        $this->providers = $providers;
        $this->failoverLimit = $failoverLimit;
    }

    /**
     * Attempts to send an email using multiple providers in a failover manner.
     * If one provider fails, it retries using the next provider.
     * 
     * @param string $recipient The email address of the recipient.
     * @param string $subject The subject of the email.
     * @param string $body The body content of the email.
     * @param array $templateData An associative array of dynamic data for template variables (optional).
     * 
     * Returns 'true' if the email is successfully sent, 'false' otherwise.
     * 
     * @throws \Exception If all providers fail and the failover limit is reached.
     */
    public function sendEmailOverFailoverManager(
        string $recipient, 
        string $subject, 
        string $body, 
        array $templateData = []
    ) {
        $failures = 0;

        foreach ($this->providers as $provider) {
            try {
                if ($provider->sendEmail($recipient, $subject, $body, $templateData)) {
                    return true;
                }
            } catch (\Exception $e) {
                $failures++;

                if ($failures >= $this->failoverLimit) {
                    throw new \Exception("Failover limit exceeded. Unable to send email.");
                }
            }
        }

        return false;
    }
}
