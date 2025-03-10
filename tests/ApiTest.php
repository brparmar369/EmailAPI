<?php
use PHPUnit\Framework\TestCase;
use Email\EmailApi;

/**
 * Class EmailApiTest
 * Unit tests for the EmailApiTest class.
 */
class EmailApiTest extends TestCase
{
    /**
     * Test that isJwtPresent returns true when a valid JWT is passed in the Authorization header.
     */
    public function testIsJwtPresentWithValidToken()
    {
        // Valid Authorization header with a Bearer token.
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer testJWTToken';

        $emailApi = $this->getMockBuilder(EmailApi::class)
                         ->setMethods(['getRequestInput'])
                         ->getMock();

        $emailApi->method('getRequestInput')
                 ->willReturn(['Authorization' => 'Bearer testJWTToken']);

        $this->assertTrue($emailApi->isJwtPresent());
    }

    /**
     * Test the handleRequest method when JWT token is missing.
     */
    public function testHandleRequestWhenJwtIsMissing()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_AUTHORIZATION'] = null;

        $emailApi = $this->getMockBuilder(EmailApi::class)
                         ->setMethods(['isJwtPresent'])
                         ->getMock();

        // isJwtPresent method to return false when JWT is missing.
        $emailApi->method('isJwtPresent')
                 ->willReturn(false);

        // Start output buffering
        ob_start();
        $emailApi->handleRequest();
        $output = ob_get_clean();

        // Assert that the message indicates JWT is missing
        $this->assertStringContainsString('Unauthorized: JWT token is missing.', $output);
    }

    /**
     * Test the handleRequest method with valid input.
     */
    public function testHandleRequestWithValidInput()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer testJWTToken';

        $emailApi = $this->getMockBuilder(EmailApi::class)
                         ->setMethods(['getRequestInput', 'isJwtPresent'])
                         ->getMock();

        $emailApi->method('isJwtPresent')
                 ->willReturn(true);

        $inputData = [
            'recipient' => 'test@example.com',
            'subject' => 'Test Email',
            'body' => 'Hello, this is a test email.',
        ];
        $emailApi->method('getRequestInput')
                 ->willReturn($inputData);

        // Start output buffering.
        ob_start();
        $emailApi->handleRequest();
        $output = ob_get_clean();

        // Check that the response message indicates email sent successfully
        $this->assertStringContainsString('Email sent successfully.', $output);
    }

    /**
     * Test the handleRequest method with missing required fields.
     */
    public function testHandleRequestWithInvalidInput()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer testJWTToken';

        $emailApi = $this->getMockBuilder(EmailApi::class)
                         ->setMethods(['getRequestInput', 'isJwtPresent'])
                         ->getMock();

        $emailApi->method('isJwtPresent')
                 ->willReturn(true);

        $inputData = [
            'subject' => 'Test Email',
            'body' => 'Hello, this is a test email.',
        ];
        $emailApi->method('getRequestInput')
                 ->willReturn($inputData);

        // Start output buffering.
        ob_start();
        $emailApi->handleRequest();
        $output = ob_get_clean();

        // The message indicates invalid input
        $this->assertStringContainsString('Invalid input. Please provide recipient, subject, and body.', $output);
    }
}
