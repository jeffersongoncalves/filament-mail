<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Editors;

use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Component;
use JeffersonGoncalves\FilamentMail\Contracts\TemplateEditorContract;

class RichEditorDriver implements TemplateEditorContract
{
    public function getFormField(string $fieldName = 'html_body'): Component
    {
        return RichEditor::make($fieldName)
            ->label('HTML Body')
            ->toolbarButtons([
                'bold', 'italic', 'underline', 'link',
                'bulletList', 'orderedList', 'blockquote',
                'h2', 'h3', 'undo', 'redo',
            ])
            ->columnSpanFull();
    }

    public function render(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $content = str_replace(
                ['{{'.$key.'}}', '{{ '.$key.' }}'],
                (string) $value,
                $content
            );
        }

        return $content;
    }
}
