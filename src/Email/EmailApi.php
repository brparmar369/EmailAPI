<?php

namespace Email;

/**
 * Class Api
 *
 * Handles incoming HTTP requests and sends emails via the email service.
 * This class serves as the entry point for the RESTful API.
 */
class EmailApi
{
    /**
     * EmailAPi constructor.
     *
     * Initializes the response header to `application/json`.
     */
    public function __construct()
    {
        // Prevent error if headers are already sent
        if (headers_sent()) {
            return; 
    }
        header('Content-Type: application/json');
    }

    /**
     * Handles the HTTP request.
     *
     * - Reads the input request payload.
     * - Validates the input fields.
     * - Sends the email via the configured providers with failover support.
     * - Returns a JSON response indicating success or failure.
     *
     */
    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            /*
            * Example JSON:
            * {
            *     "recipient": "test@example.com",
            *     "subject": "Test Email",
            *     "body": "Hello {username}, welcome to our service! Your first name is {firstname}.",
            *     "templateData": {
            *         "username": "Test User",
            *         "firstname": "New Test"
            *     }
            * }
            */
            // Validate required fields
            if (isset($input['recipient'], $input['subject'], $input['body'])) {
                $recipient = $input['recipient'];
                $subject = $input['subject'];
                $body = $input['body'];
                $templateData = $input['templateData'] ?? [];

                // Providers
                $providers = [new MailChimpProvider(), new SMTPProvider()];
                
                // Failover Manager
                $failoverManager = new EmailServiceFailoverManager($providers);
                
                // Email Service with failoverManager dependency
                $emailService = new EmailService($failoverManager);

                // Send email
                $result = $emailService->sendEmailService($recipient, $subject, $body, $templateData);

                // Response
                echo json_encode([
                    'message' => $result ? 'Email sent successfully.' : 'Failed to send email.',
                ]);
            } else {
                echo json_encode([
                    'message' => 'Invalid input. Please provide recipient, subject, and body.',
                ]);
            }
        } else {
            echo json_encode([
                'message' => 'Only POST method is allowed.',
            ]);
        }
    }
}
