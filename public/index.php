<?php

// Autoload the necessary classes using autoloading
spl_autoload_register(function (string $class) {
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
 * Expected JSON:
 * - recipient: string - The email address of the recipient.
 * - subject: string - The subject of the email.
 * - body: string - The body content of the email.
 * - templateData (optional): associative array for dynamic template data ({username}).
 *
 */

// Create an instance of the API class
$api = new EmailAPi();

// Handle the HTTP request and send the email
$api->handleRequest();
