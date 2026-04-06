<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Traits;

use JeffersonGoncalves\FilamentMail\Contracts\TemplateEditorContract;
use JeffersonGoncalves\LaravelMail\Models\MailTemplate;

trait HasMailTemplate
{
    protected string $templateKey;

    /** @var array<string, mixed> */
    protected array $templateVariables = [];

    protected ?string $templateLocale = null;

    public function buildContent(): static
    {
        $modelClass = config('laravel-mail.models.mail_template', MailTemplate::class);
        $template = $modelClass::where('key', $this->templateKey)->where('is_active', true)->firstOrFail();
        $editor = app(TemplateEditorContract::class);
        $locale = $this->templateLocale ?? app()->getLocale();

        $this->subject($editor->render($template->getSubjectForLocale($locale), $this->templateVariables));

        return $this->view(
            'filament-mail::emails.template',
            ['body' => $editor->render($template->getHtmlBodyForLocale($locale), $this->templateVariables)]
        );
    }
}
