<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Resources\MailLog\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use JeffersonGoncalves\FilamentMail\Resources\MailLog\MailLogResource;
use JeffersonGoncalves\LaravelMail\Actions\ResendMailAction;
use JeffersonGoncalves\LaravelMail\Actions\RetryFailedMailAction;
use JeffersonGoncalves\LaravelMail\Enums\MailStatus;
use JeffersonGoncalves\LaravelMail\Models\MailLog;

/**
 * @property MailLog $record
 */
class ViewMailLog extends ViewRecord
{
    protected static string $resource = MailLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('resend')
                ->label('Resend')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Resend Email')
                ->modalDescription('Are you sure you want to resend this email? A new email will be sent to the original recipients.')
                ->action(function () {
                    app(ResendMailAction::class)->execute($this->record);

                    Notification::make()
                        ->title('Email resent successfully')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('retry')
                ->label('Retry')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Retry Failed Email')
                ->modalDescription('This will attempt to resend the failed email.')
                ->visible(fn () => in_array($this->record->status, [MailStatus::Failed, MailStatus::Bounced])
                    && config('laravel-mail.retry.enabled', false))
                ->action(function () {
                    $result = app(RetryFailedMailAction::class)->execute($this->record);

                    if ($result) {
                        Notification::make()
                            ->title('Email retry queued')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Retry failed')
                            ->body('Maximum retry attempts exceeded or retry is disabled.')
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->visible(fn () => $this->record->html_body !== null)
                ->modalContent(fn () => view('filament-mail::components.mail-preview-modal', [
                    'html' => $this->record->html_body,
                    'subject' => $this->record->subject,
                ]))
                ->modalHeading(fn () => $this->record->subject ?? 'Email Preview')
                ->modalWidth('7xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),

            Actions\DeleteAction::make(),
        ];
    }
}
