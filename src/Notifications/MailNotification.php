<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use JeffersonGoncalves\FilamentMail\Contracts\TemplateEditorContract;
use JeffersonGoncalves\LaravelMail\Models\MailTemplate;

class MailNotification extends Notification
{
    /**
     * @param  string  $templateKey  Template key (e.g. 'auth.welcome')
     * @param  array<string, mixed>  $variables  Variables for subject and body replacement
     * @param  array<string, mixed>  $metadata  Extra data: cc, bcc, attachments, locale, reply_to
     */
    public function __construct(
        protected string $templateKey,
        protected array $variables = [],
        protected array $metadata = [],
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $locale = $this->metadata['locale']
            ?? app()->getLocale();

        $template = $this->resolveTemplate($locale);
        $editor = app(TemplateEditorContract::class);

        $subject = $editor->render($template->getSubjectForLocale($locale), $this->variables);
        $body = $editor->render($template->getHtmlBodyForLocale($locale), $this->variables);

        $message = (new MailMessage)
            ->subject($subject)
            ->view(
                'filament-mail::emails.template',
                ['body' => $body, 'template' => $template]
            );

        foreach ($this->metadata['cc'] ?? [] as $cc) {
            $message->cc($cc);
        }

        foreach ($this->metadata['bcc'] ?? [] as $bcc) {
            $message->bcc($bcc);
        }

        if (isset($this->metadata['reply_to'])) {
            $message->replyTo($this->metadata['reply_to']);
        }

        foreach ($this->metadata['attachments'] ?? [] as $attachment) {
            $message->attach($attachment);
        }

        return $message;
    }

    protected function resolveTemplate(string $locale): MailTemplate
    {
        $modelClass = config('laravel-mail.models.mail_template', MailTemplate::class);

        return $modelClass::query()
            ->where('key', $this->templateKey)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
