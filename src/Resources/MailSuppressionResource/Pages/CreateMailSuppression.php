<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources\MailSuppressionResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentMail\Resources\MailSuppressionResource;

class CreateMailSuppression extends CreateRecord
{
    protected static string $resource = MailSuppressionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['suppressed_at'] = now();

        return $data;
    }
}
