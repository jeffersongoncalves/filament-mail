<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;
use JeffersonGoncalves\FilamentMail\Resources\MailLogResource\Pages;
use JeffersonGoncalves\LaravelMail\Enums\MailStatus;
use JeffersonGoncalves\LaravelMail\Models\MailLog;

class MailLogResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?int $navigationSort = 1;

    public static function getModel(): string
    {
        return config('laravel-mail.models.mail_log', MailLog::class);
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentMailPlugin::get()->getNavigationGroup();
    }

    public static function getModelLabel(): string
    {
        return config('filament-mail.resources.mail_log.label', 'Mail Log');
    }

    public static function getPluralModelLabel(): string
    {
        return config('filament-mail.resources.mail_log.plural_label', 'Mail Logs');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (MailStatus $state): string => match ($state) {
                        MailStatus::Pending => 'gray',
                        MailStatus::Sent => 'info',
                        MailStatus::Delivered => 'success',
                        MailStatus::Bounced => 'danger',
                        MailStatus::Complained => 'warning',
                        MailStatus::Failed => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(50)
                    ->weight(FontWeight::Medium),

                Tables\Columns\TextColumn::make('to')
                    ->label('To')
                    ->formatStateUsing(function ($state): string {
                        if (is_array($state) && count($state) > 0) {
                            return $state[0]['email'] ?? '';
                        }

                        return '';
                    })
                    ->searchable(query: function ($query, string $search) {
                        $query->where('to', 'like', "%{$search}%");
                    })
                    ->limit(30),

                Tables\Columns\TextColumn::make('mailer')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('template.name')
                    ->label('Template')
                    ->toggleable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('attachments')
                    ->label('Attachments')
                    ->formatStateUsing(fn ($state): string => is_array($state) ? (string) count($state) : '0')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(MailStatus::cases())->mapWithKeys(
                        fn (MailStatus $status) => [$status->value => ucfirst($status->value)]
                    )->all()),

                Tables\Filters\SelectFilter::make('mailer')
                    ->options(fn () => config('laravel-mail.models.mail_log', MailLog::class)::query()
                        ->whereNotNull('mailer')
                        ->distinct()
                        ->pluck('mailer', 'mailer')
                        ->all()),

                Tables\Filters\TernaryFilter::make('has_attachments')
                    ->label('Has Attachments')
                    ->queries(
                        true: fn ($query) => $query->whereRaw('json_array_length(attachments) > 0'),
                        false: fn ($query) => $query->where(function ($q) {
                            $q->whereNull('attachments')
                                ->orWhereRaw('json_array_length(attachments) = 0');
                        }),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (MailStatus $state): string => match ($state) {
                                MailStatus::Pending => 'gray',
                                MailStatus::Sent => 'info',
                                MailStatus::Delivered => 'success',
                                MailStatus::Bounced => 'danger',
                                MailStatus::Complained => 'warning',
                                MailStatus::Failed => 'danger',
                            }),
                        TextEntry::make('subject'),
                        TextEntry::make('mailer')
                            ->placeholder('—'),
                        TextEntry::make('provider_message_id')
                            ->label('Provider Message ID')
                            ->placeholder('—')
                            ->columnSpanFull()
                            ->copyable(),
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ]),

                Section::make('Sender & Recipients')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('from')
                            ->formatStateUsing(fn ($state) => self::formatAddresses($state)),
                        TextEntry::make('to')
                            ->formatStateUsing(fn ($state) => self::formatAddresses($state)),
                        TextEntry::make('cc')
                            ->formatStateUsing(fn ($state) => self::formatAddresses($state))
                            ->placeholder('—'),
                        TextEntry::make('bcc')
                            ->formatStateUsing(fn ($state) => self::formatAddresses($state))
                            ->placeholder('—'),
                        TextEntry::make('reply_to')
                            ->label('Reply To')
                            ->formatStateUsing(fn ($state) => self::formatAddresses($state))
                            ->placeholder('—'),
                    ]),

                Section::make('Content')
                    ->schema([
                        Tabs::make('content_tabs')
                            ->tabs([
                                Tab::make('HTML')
                                    ->schema([
                                        ViewEntry::make('html_body')
                                            ->view('filament-mail::components.mail-preview-entry')
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('Plain Text')
                                    ->schema([
                                        TextEntry::make('text_body')
                                            ->prose()
                                            ->placeholder('No plain text version'),
                                    ]),
                            ]),
                    ]),

                Section::make('Headers')
                    ->schema([
                        TextEntry::make('headers')
                            ->formatStateUsing(function ($state): string {
                                if (! is_array($state) || empty($state)) {
                                    return '—';
                                }

                                return collect($state)
                                    ->map(fn ($value, $key) => is_int($key) ? $value : "{$key}: {$value}")
                                    ->implode("\n");
                            })
                            ->prose()
                            ->placeholder('—'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Attachments')
                    ->schema([
                        ViewEntry::make('attachments')
                            ->view('filament-mail::components.attachments-entry')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => is_array($record->attachments) && count($record->attachments) > 0),

                Section::make('Metadata')
                    ->schema([
                        TextEntry::make('metadata')
                            ->formatStateUsing(function ($state): string {
                                if (! is_array($state) || empty($state)) {
                                    return '—';
                                }

                                return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                            })
                            ->prose()
                            ->placeholder('—'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Template')
                    ->schema([
                        TextEntry::make('template.name')
                            ->label('Template')
                            ->url(fn ($record) => $record->mail_template_id
                                ? MailTemplateResource::getUrl('edit', ['record' => $record->mail_template_id])
                                : null),
                        TextEntry::make('template.key')
                            ->label('Template Key'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record->mail_template_id !== null),

                Section::make('Tracking Events')
                    ->schema([
                        ViewEntry::make('tracking_events')
                            ->view('filament-mail::components.tracking-events-entry')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => $record->trackingEvents()->exists()),
            ]);
    }

    protected static function formatAddresses($state): string
    {
        if (! is_array($state) || empty($state)) {
            return '—';
        }

        return collect($state)
            ->map(function ($address) {
                $email = $address['email'] ?? '';
                $name = $address['name'] ?? '';

                return $name ? "{$name} <{$email}>" : $email;
            })
            ->implode(', ');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailLogs::route('/'),
            'view' => Pages\ViewMailLog::route('/{record}'),
        ];
    }
}
