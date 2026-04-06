<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Editors;

use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use JeffersonGoncalves\FilamentMail\Contracts\TemplateEditorContract;
use JeffersonGoncalves\FilamentMail\Forms\Components\UnlayerField;

class UnlayerEditorDriver implements TemplateEditorContract
{
    public function getFormField(string $fieldName = 'html_body'): Component
    {
        return Group::make([
            Hidden::make('body_design'),
            UnlayerField::make($fieldName)
                ->label('Template Design')
                ->columnSpanFull(),
        ])->columnSpanFull();
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
