<?php

// Autoload the necessary classes using PSR-4 autoloading
spl_autoload_register(function (string $class): void {
    $baseDir = __DIR__ . '/../src/';
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use Email\EmailAPi;

/**
 * Entry point for the Email API.
 *
 * Expected JSON payload:
 * - recipient: string - The email address of the recipient.
 * - subject: string - The subject of the email.
 * - body: string - The body content of the email.
 * - templateData (optional): array - Key-value pairs for dynamic template data (e.g., {username}).
 *
 */

// Create an instance of the API class
$api = new EmailAPi();

// Handle the HTTP request and send the email
$api->handleRequest();
