<?php
use PHPUnit\Framework\TestCase;
use Email\TemplateEngine;

/**
 * Class TemplateEngineTest
 * This class contains test cases for the TemplateEngine class.
 */
class TemplateEngineTest extends TestCase {

    /**
     * Test applying a template with valid data.
     * This test checks that the TemplateEngine correctly replaces placeholders in the template with dynamic data.
     * It verifies that {username} gets replaced by the actual username.
     */
    public function testApplyTemplate() {
        $template = 'Hello {username}, welcome!';
        $data = ['username' => 'TestUser'];

        $result = TemplateEngine::applyTemplate($template, $data);

        $this->assertEquals('Hello TestUser, welcome!', $result);
    }

    /**
     * Test applying a template with missing data.
     * 
     * This test ensures that the TemplateEngine handles missing placeholders gracefully and doesn't break.
     * It checks if the template remains unchanged when the corresponding data is not provided.
     */
    public function testApplyTemplateMissingData() {
        $template = 'Hello {username}, welcome!';
        $data = [];

        $result = TemplateEngine::applyTemplate($template, $data);

        $this->assertEquals('Hello {username}, welcome!', $result);
    }
}
