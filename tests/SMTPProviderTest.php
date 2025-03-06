<?php
namespace Tests\Email;

use PHPUnit\Framework\TestCase;
use Email\SMTPProvider;

/**
 * Class SMTPProviderTest
 * 
 * This class contains test cases for the SMTPProvider class.
 * It tests the 'sendEmail' method to ensure it behaves as expected when sending emails.
 */
class SMTPProviderTest extends TestCase {

    /**
     * Test the sendEmail method for SMTPProvider.
     * 
     * This test checks that the SMTPProvider correctly sends an email when given valid parameters.
     * It ensures that the method returns 'true' as expected.
     */
    public function testSendEmailValid() {
        $smtpProvider = new SMTPProvider();
        
        // valid email sending
        $result = $smtpProvider->sendEmail('test@example.com', 'Test Subject', 'Test Body', ['username' => 'TestUser']);
        
        // Assert that the email was successfully sent.
        $this->assertTrue($result);
    }

    /**
     * Test the sendEmail method with an invalid email address.
     * 
     * This test checks that the SMTPProvider handles an invalid email address properly.
     */
    public function testSendEmailInvalid() {
        $smtpProvider = new SMTPProvider();
        
        // pass an invalid email
        $result = $smtpProvider->sendEmail('invalid email', 'Test Subject', 'Test Body');
        
        // Assert that the result is false for invalid email
        $this->assertFalse($result);
    }
}
