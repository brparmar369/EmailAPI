<?php

namespace Email;

/**
 * Class TemplateEngine
 * 
 * Provides methods to apply dynamic data to email templates.
 * This class replaces placeholders in an email body with actual values.
 */
class TemplateEngine
{
    /**
     * Applies dynamic template data to an email body.
     * 
     * @param string $template The email body with placeholders.
     * @param array $data An associative array of dynamic data to replace the placeholders.
     * 
     * @return string The email body with replaced dynamic content.
     */
    public static function applyTemplate(string $template, array $data)
    {
        /*
         * Example:
         * $template = "Hello {username}, welcome to our service!";
         * $data = ["username" => "TestUser"];
         */
        foreach ($data as $key => $value) {
            $template = str_replace("{" . $key . "}", $value, $template);
        }

        return $template;
    }
}
