<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Contracts;

use Filament\Schemas\Components\Component;

interface TemplateEditorContract
{
    /**
     * Returns the Filament form field for the resource schema.
     */
    public function getFormField(string $fieldName = 'html_body'): Component;

    /**
     * Renders the content replacing variables.
     *
     * @param  array<string, mixed>  $variables
     */
    public function render(string $content, array $variables): string;
}
