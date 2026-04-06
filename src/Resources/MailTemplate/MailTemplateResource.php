<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources\MailTemplate;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;
use JeffersonGoncalves\FilamentMail\Resources\MailTemplate\Schemas\MailTemplateForm;
use JeffersonGoncalves\FilamentMail\Resources\MailTemplate\Schemas\MailTemplateInfolist;
use JeffersonGoncalves\FilamentMail\Resources\MailTemplate\Tables\MailTemplatesTable;
use JeffersonGoncalves\LaravelMail\Models\MailTemplate;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class MailTemplateResource extends Resource
{
    use Translatable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    public static function getModel(): string
    {
        return config('laravel-mail.models.mail_template', MailTemplate::class);
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentMailPlugin::get()->getNavigationGroup();
    }

    public static function getModelLabel(): string
    {
        return config('filament-mail.resources.mail_template.label', 'Mail Template');
    }

    public static function getPluralModelLabel(): string
    {
        return config('filament-mail.resources.mail_template.plural_label', 'Mail Templates');
    }

    public static function form(Schema $schema): Schema
    {
        return MailTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MailTemplatesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MailTemplateInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailTemplates::route('/'),
            'create' => Pages\CreateMailTemplate::route('/create'),
            'edit' => Pages\EditMailTemplate::route('/{record}/edit'),
            'view' => Pages\ViewMailTemplate::route('/{record}'),
        ];
    }
}
