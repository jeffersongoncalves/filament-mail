<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentMail\Contracts\TemplateEditorContract;
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;
use JeffersonGoncalves\FilamentMail\Resources\MailTemplateResource\Pages;
use JeffersonGoncalves\LaravelMail\Models\MailTemplate;

class MailTemplateResource extends Resource
{
    use Translatable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn (?string $operation) => $operation === 'edit')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\TextInput::make('layout')
                            ->label('Layout')
                            ->placeholder('emails.layout')
                            ->helperText('Blade layout to wrap the template content')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->required(),
                        app(TemplateEditorContract::class)
                            ->getFormField('html_body'),
                    ]),

                Section::make('Variables')
                    ->schema([
                        Forms\Components\Repeater::make('variables')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'string' => 'String',
                                        'int' => 'Integer',
                                        'bool' => 'Boolean',
                                        'array' => 'Array',
                                        'model' => 'Model',
                                    ])
                                    ->default('string')
                                    ->required(),
                                Forms\Components\TextInput::make('example')
                                    ->label('Example Value'),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('Add Variable')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Locales')
                    ->formatStateUsing(function ($record): string {
                        $translations = $record->getTranslations('subject');

                        return collect(array_keys($translations))->implode(', ');
                    })
                    ->badge(),

                Tables\Columns\TextColumn::make('versions_count')
                    ->counts('versions')
                    ->label('Versions')
                    ->sortable(),

                Tables\Columns\TextColumn::make('logs_count')
                    ->counts('logs')
                    ->label('Emails Sent')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Details')
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

                \Filament\Infolists\Components\Section::make('Preview')
                    ->schema([
                        ViewEntry::make('preview')
                            ->view('filament-mail::components.template-preview-entry')
                            ->columnSpanFull(),
                    ]),

                \Filament\Infolists\Components\Section::make('Variables')
                    ->schema([
                        ViewEntry::make('variables')
                            ->view('filament-mail::components.variables-entry')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => ! empty($record->variables)),

                \Filament\Infolists\Components\Section::make('Version History')
                    ->schema([
                        ViewEntry::make('versions')
                            ->view('filament-mail::components.versions-entry')
                            ->columnSpanFull(),
                    ]),
            ]);
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
