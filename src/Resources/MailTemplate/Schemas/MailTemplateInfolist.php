<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources\MailTemplate\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class MailTemplateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $locales = config('filament-mail.template_editor.locales', ['en']);

        return $schema
            ->components([
                Section::make('Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('key')
                            ->copyable(),
                        TextEntry::make('name'),
                        TextEntry::make('is_active')
                            ->label('Active')
                            ->badge()
                            ->color(fn (bool $state) => $state ? 'success' : 'gray')
                            ->formatStateUsing(fn (bool $state) => $state ? 'Yes' : 'No'),
                        TextEntry::make('layout')
                            ->placeholder('—'),
                    ]),

                Section::make('Preview by Locale')
                    ->schema([
                        Tabs::make('preview_tabs')
                            ->tabs(
                                collect($locales)->map(function (string $locale) {
                                    return Tab::make(strtoupper($locale))
                                        ->schema([
                                            ViewEntry::make("preview_{$locale}")
                                                ->view('filament-mail::components.template-preview-entry', [
                                                    'locale' => $locale,
                                                ])
                                                ->columnSpanFull(),
                                        ]);
                                })->all()
                            ),
                    ]),

                Section::make('Variables')
                    ->schema([
                        ViewEntry::make('variables')
                            ->view('filament-mail::components.variables-entry')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => ! empty($record->variables)),

                Section::make('Version History')
                    ->schema([
                        ViewEntry::make('versions')
                            ->view('filament-mail::components.versions-entry')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
