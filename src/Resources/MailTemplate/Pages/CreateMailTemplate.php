<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources\MailTemplate\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentMail\Resources\MailTemplate\MailTemplateResource;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateMailTemplate extends CreateRecord
{
    use Translatable;

    protected static string $resource = MailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
