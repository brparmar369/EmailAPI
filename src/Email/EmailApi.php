<?php

namespace Email;

/**
 * Class Api
 *
 * Handles incoming HTTP requests and sends emails via the email service.
 * This class serves as the entry point for the Email API.
 */
class EmailApi
{
    /**
     * EmailApi constructor.
     *
     * Initializes the response header to 'application/json'.
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
     * - Validate the JWT Token is set or not.
     * - Reads the input request payload.
     * - Validates the input fields.
     * - Sends the email via the providers with failover support.
     * - Returns a JSON response indicating success or failure.
     *
     */
    public function handleRequest()
    {
        // Checking the JWT token is present.
        if (!$this->isJwtPresent()) {
            echo json_encode([
                'message' => 'Unauthorized: JWT token is missing.',
            ]);
            return;
        }

        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Using method for input reading
            $input = $this->getRequestInput();
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

                // Providers array for passing in failoverManager
                $providers = [new MailChimpProvider(), new SMTPProvider()];

                // Failover Manager
                $failoverManager = new EmailFailoverManager($providers);

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

    /**
     * Checking if JWT token is present in the Authorization header.
     * Return True if the JWT token is present, false otherwise.
     */
    public function isJwtPresent()
    {
        // Check if Authorization exists.
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        
            // Checking if the Authorization header starts with "Bearer "
            if (strpos($authHeader, 'Bearer ') === 0) {
                //  Remove the Bearer string.
                $token = substr($authHeader, 7);
                
                if (!empty($token)) {
                    return true;
                }
            }
        }        
        return false;
    }
    /**
     * Reading of input data.
     */
    public function getRequestInput()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
