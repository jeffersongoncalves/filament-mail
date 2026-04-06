<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources\MailTemplateResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentMail\Resources\MailTemplateResource;

class CreateMailTemplate extends CreateRecord
{
    protected static string $resource = MailTemplateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return self::processTranslations($data);
    }

    public static function processTranslations(array $data): array
    {
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        foreach (['subject', 'html_body', 'text_body'] as $field) {
            $fieldTranslations = [];
            foreach ($translations as $locale => $fields) {
                if (! empty($fields[$field])) {
                    $fieldTranslations[$locale] = $fields[$field];
                }
            }
            if (! empty($fieldTranslations)) {
                $data[$field] = $fieldTranslations;
            }
        }

        return $data;
    }
}
