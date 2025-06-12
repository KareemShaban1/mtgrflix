<?php
namespace App\Http\Services;

use TomatoPHP\FilamentSettingsHub\Models\Setting;

class SendMessage
{
    public function getTemplate(string $templateName, array $variables = []): string
    {
        $template = Setting::where('name', 'whatsapp_template_' . $templateName)
                        
                         ->firstOrFail();
        
        return $this->replaceVariables($template->payload, $variables);
    }

    /**
     * Replace placeholders with actual values
     */
    protected function replaceVariables(string $message, array $variables): string
    {
        // Default replacements if needed
        $defaults = [
            '{{app_name}}' => config('app.name'),
            // Add other default variables here
        ];
        
        // Merge with provided variables
        $replacements = array_merge($defaults, $variables);
        
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $message
        );
    }
}