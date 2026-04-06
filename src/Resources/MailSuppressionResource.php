<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;
use JeffersonGoncalves\FilamentMail\Resources\MailSuppressionResource\Pages;
use JeffersonGoncalves\LaravelMail\Enums\SuppressionReason;
use JeffersonGoncalves\LaravelMail\Models\MailSuppression;

class MailSuppressionResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';

    protected static ?int $navigationSort = 3;

    public static function getModel(): string
    {
        return config('laravel-mail.models.mail_suppression', MailSuppression::class);
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentMailPlugin::get()->getNavigationGroup();
    }

    public static function getModelLabel(): string
    {
        return config('filament-mail.resources.mail_suppression.label', 'Suppression');
    }

    public static function getPluralModelLabel(): string
    {
        return config('filament-mail.resources.mail_suppression.plural_label', 'Suppressions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('reason')
                            ->options(collect(SuppressionReason::cases())->mapWithKeys(
                                fn (SuppressionReason $reason) => [$reason->value => match ($reason) {
                                    SuppressionReason::HardBounce => 'Hard Bounce',
                                    SuppressionReason::Complaint => 'Complaint',
                                    SuppressionReason::Manual => 'Manual',
                                }]
                            )->all())
                            ->default(SuppressionReason::Manual->value)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('reason')
                    ->badge()
                    ->color(fn (SuppressionReason $state): string => match ($state) {
                        SuppressionReason::HardBounce => 'danger',
                        SuppressionReason::Complaint => 'warning',
                        SuppressionReason::Manual => 'gray',
                    })
                    ->formatStateUsing(fn (SuppressionReason $state): string => match ($state) {
                        SuppressionReason::HardBounce => 'Hard Bounce',
                        SuppressionReason::Complaint => 'Complaint',
                        SuppressionReason::Manual => 'Manual',
                    }),

                Tables\Columns\TextColumn::make('provider')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('suppressed_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mailLog.subject')
                    ->label('Related Email')
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('reason')
                    ->options(collect(SuppressionReason::cases())->mapWithKeys(
                        fn (SuppressionReason $reason) => [$reason->value => match ($reason) {
                            SuppressionReason::HardBounce => 'Hard Bounce',
                            SuppressionReason::Complaint => 'Complaint',
                            SuppressionReason::Manual => 'Manual',
                        }]
                    )->all()),

                Tables\Filters\SelectFilter::make('provider')
                    ->options(fn () => config('laravel-mail.models.mail_suppression', MailSuppression::class)::query()
                        ->whereNotNull('provider')
                        ->distinct()
                        ->pluck('provider', 'provider')
                        ->all()),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Unsuppress'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Unsuppress Selected'),
                ]),
            ])
            ->defaultSort('suppressed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailSuppressions::route('/'),
            'create' => Pages\CreateMailSuppression::route('/create'),
        ];
    }
}
