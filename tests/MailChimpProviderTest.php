<?php
namespace Tests\Email;

use PHPUnit\Framework\TestCase;
use Email\MailChimpProvider;

/**
 * Class MailChimpProviderTest
 * 
 * This class contains test cases for the MailChimpProvider class.
 * It tests the 'sendEmail' method to ensure it behaves as expected when sending emails.
 */
class MailChimpProviderTest extends TestCase {

    /**
     * Test the sendEmail method for MailChimpProvider.
     * 
     * This test checks that the MailChimpProvider correctly sends an email when given valid parameters.
     * It ensures that the method returns 'true' as expected.
     */
    public function testSendEmailValid() {
        $mailChimpProvider = new MailChimpProvider();
        
        // valid email sending
        $result = $mailChimpProvider->sendEmail('test@example.com', 'Test Subject', 'Test Body', ['username' => 'TestUser']);
        
        // Assert that the email was successfully sent.
        $this->assertTrue($result);
    }

    /**
     * Test the sendEmail method with an invalid email address.
     * 
     * This test checks that the MailChimpProvider handles an invalid email address properly.
     */
    public function testSendEmailInvalid() {
        $mailChimpProvider = new MailChimpProvider();
        
        // pass an invalid email
        $result = $mailChimpProvider->sendEmail('invalid email', 'Test Subject', 'Test Body');
        
        // Assert that the result is false for invalid email
        $this->assertFalse($result);
    }
}
