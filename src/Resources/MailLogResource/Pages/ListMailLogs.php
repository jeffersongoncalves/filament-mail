<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources\MailLogResource\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentMail\Resources\MailLogResource;

class ListMailLogs extends ListRecords
{
    protected static string $resource = MailLogResource::class;
}
