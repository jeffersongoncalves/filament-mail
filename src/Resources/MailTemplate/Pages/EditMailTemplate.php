<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources\MailTemplate\Pages;

use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use JeffersonGoncalves\FilamentMail\Resources\MailTemplate\MailTemplateResource;
use JeffersonGoncalves\LaravelMail\Actions\PreviewTemplateAction;
use JeffersonGoncalves\LaravelMail\Mail\TemplateNotificationMailable;
use JeffersonGoncalves\LaravelMail\Models\MailTemplate;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

/**
 * @property MailTemplate $record
 */
class EditMailTemplate extends EditRecord
{
    use Translatable;

    protected static string $resource = MailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        $locales = static::getResource()::getTranslatableLocales();

        return [
            LocaleSwitcher::make(),

            Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->modalContent(fn () => view('filament-mail::components.mail-preview-modal', [
                    'html' => app(PreviewTemplateAction::class)->execute($this->record)['html'],
                    'subject' => app(PreviewTemplateAction::class)->execute($this->record)['subject'],
                ]))
                ->modalHeading('Template Preview')
                ->modalWidth('7xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),

            Actions\Action::make('sendTest')
                ->label('Send Test')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->form([
                    TextInput::make('email')
                        ->label('Recipient Email')
                        ->email()
                        ->required(),
                    Select::make('locale')
                        ->label('Locale')
                        ->options(collect($locales)->mapWithKeys(fn ($l) => [$l => strtoupper($l)])->all())
                        ->default($locales[0] ?? 'en'),
                ])
                ->action(function (array $data) {
                    $exampleData = collect($this->record->variables ?? [])
                        ->mapWithKeys(fn (array $var) => [$var['name'] => $var['example']])
                        ->all();

                    if (! empty($data['locale'])) {
                        app()->setLocale($data['locale']);
                    }

                    Mail::to($data['email'])
                        ->send(new TemplateNotificationMailable(
                            $this->record->key,
                            $exampleData,
                        ));

                    Notification::make()
                        ->title('Test email sent')
                        ->body("Sent to {$data['email']}")
                        ->success()
                        ->send();
                }),

            Actions\Action::make('duplicate')
                ->label('Duplicate')
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function () {
                    $clone = $this->record->replicate(['id']);
                    $clone->key = $this->record->key.'-copy';
                    $clone->name = $this->record->name.' (Copy)';
                    $clone->save();

                    Notification::make()
                        ->title('Template duplicated')
                        ->success()
                        ->send();

                    return redirect(MailTemplateResource::getUrl('edit', ['record' => $clone]));
                }),

            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
