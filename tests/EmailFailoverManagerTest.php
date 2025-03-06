<?php

use PHPUnit\Framework\TestCase;
use Email\EmailFailoverManager;
use Email\EmailServiceProvider;
use Exception;

/**
 * Class EmailFailoverManagerTest
 * Unit tests for the EmailFailoverManager class.
 * These tests verify that the failover manager work as expected when sending emails using multiple providers.
 */
class EmailFailoverManagerTest extends TestCase
{
    /**
     * Test that sendEmailOverFailoverManager returns true when the first provider succeeds. 
     * In this test, the first provider successfully sends the email, and the failover mechanism does not trigger.
     */
    public function testSuccessFirstProvider()
    {
        // create a mock of EmailServiceProvider
        $providerMock = $this->createMock(EmailServiceProvider::class);
        $providerMock->method('sendEmail')
                     ->willReturn(true);

        // Instantiate the failover manager with a single provider
        $failoverManager = new EmailFailoverManager([$providerMock]);

        // Call the method to test
        $result = $failoverManager->sendEmailOverFailoverManager('test@example.com', 'Subject', 'Body');

        // Assert that the result is true.
        $this->assertTrue($result);
    }

    /**
     * Test that sendEmailOverFailoverManager fails if the first provider fails, 
     * but the second provider succeeds.
     * In this case, the first provider fails, but the second one successfully sends the email.
     */
    public function testSuccessSecondProvider()
    {
        // create a mock of EmailServiceProvider
        $provider1Mock = $this->createMock(EmailServiceProvider::class);
        $provider1Mock->method('sendEmail')
                      ->willThrowException(new Exception("Provider 1 failed"));

        $provider2Mock = $this->createMock(EmailServiceProvider::class);
        $provider2Mock->method('sendEmail')
                      ->willReturn(true);

        // Instantiate the failover manager with two providers
        $failoverManager = new EmailFailoverManager([$provider1Mock, $provider2Mock]);

        // Call the method to test
        $result = $failoverManager->sendEmailOverFailoverManager('test@example.com', 'Subject', 'Body');

        // Assert that the result is true (indicating email was sent successfully on the second provider)
        $this->assertTrue($result);
    }

    /**
     * Test that sendEmailOverFailoverManager returns false if all providers fail.
     * 
     * In this case, both providers fail, and the failover manager returns false.
     */
    public function testAllProvidersFail()
    {
        // create a mock of EmailServiceProvider
        $provider1Mock = $this->createMock(EmailServiceProvider::class);
        $provider1Mock->method('sendEmail')
                      ->willThrowException(new Exception("Provider 1 failed"));

        $provider2Mock = $this->createMock(EmailServiceProvider::class);
        $provider2Mock->method('sendEmail')
                      ->willThrowException(new Exception("Provider 2 failed"));

        // Instantiate the failover manager with two providers
        $failoverManager = new EmailFailoverManager([$provider1Mock, $provider2Mock]);

        // Call the method to test
        $result = $failoverManager->sendEmailOverFailoverManager('test@example.com', 'Subject', 'Body');

        // Assert that the result is false
        $this->assertFalse($result);
    }

    /**
     * Test that sendEmailOverFailoverManager throws an exception when the failover limit is exceeded.
     * 
     * This test verifies that the failover manager throws an exception when all providers fail 
     * and the failover limit is reached.
     */
    public function testFailoverLimitExceeded()
    {
        // create a mock of EmailServiceProvider
        $provider1Mock = $this->createMock(EmailServiceProvider::class);
        $provider1Mock->method('sendEmail')
                      ->willThrowException(new Exception("Provider 1 failed"));

        $provider2Mock = $this->createMock(EmailServiceProvider::class);
        $provider2Mock->method('sendEmail')
                      ->willThrowException(new Exception("Provider 2 failed"));

        $provider3Mock = $this->createMock(EmailServiceProvider::class);
        $provider3Mock->method('sendEmail')
                      ->willThrowException(new Exception("Provider 3 failed"));

        // Instantiate the failover manager with three providers and a failover limit.
        $failoverManager = new EmailFailoverManager([$provider1Mock, $provider2Mock, $provider3Mock], 3);

        // Assert that the method throws an exception due to exceeding the failover limit
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Failover limit exceeded. Unable to send email.");

        // Call the method.
        $failoverManager->sendEmailOverFailoverManager('test@example.com', 'Subject', 'Body');
    }
}
