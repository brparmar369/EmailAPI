<?php

use PHPUnit\Framework\TestCase;
use Email\EmailService;
use Email\EmailFailoverManager;

/**
 * Class EmailServiceTest
 *
 * Unit tests for the EmailService class.
 * These tests verify that the sendEmailService method behaves as expected in different scenarios.
 */
class EmailServiceTest extends TestCase
{
    /**
     * Test that sendEmailService returns true when the email is sent successfully.
     */
    public function testSendEmailServiceSuccess()
    {
        // Create a mock object for EmailFailoverManager
        $failoverManagerMock = $this->createMock(EmailFailoverManager::class);
        $failoverManagerMock->method('sendEmailOverFailoverManager')
                            ->willReturn(true);
        $emailService = new EmailService($failoverManagerMock);

        // Call sendEmailService
        $result = $emailService->sendEmailService('test@example.com', 'Subject', 'Body');

        // Assert that the result is true
        $this->assertTrue($result);
    }

    /**
     * Test that sendEmailService returns false when email sending fails.
     */
    public function testSendEmailServiceFailure()
    {
        // Create a mock object for EmailFailoverManager
        $failoverManagerMock = $this->createMock(EmailFailoverManager::class);
        $failoverManagerMock->method('sendEmailOverFailoverManager')
                            ->willReturn(false);
        $emailService = new EmailService($failoverManagerMock);

        // Call sendEmailService
        $result = $emailService->sendEmailService('test@example.com', 'Subject', 'Body');

        // Assert that the result is false/
        $this->assertFalse($result);
    }
}
