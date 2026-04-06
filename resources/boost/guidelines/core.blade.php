## Filament Mail

A complete email management UI for Filament, built on top of `jeffersongoncalves/laravel-mail`. Provides mail logs with preview, database templates with multi-locale editing, delivery tracking, analytics dashboard, and suppression management.

### Key Features

- **Mail Logs**: Browse, search, view sent emails with HTML preview, resend, retry
- **Mail Templates**: Database-driven templates with multi-locale support via `spatie/laravel-translatable`
- **Swappable Template Editor**: `rich_editor` (default) or `unlayer` (visual drag-and-drop)
- **MailNotification**: Send template-based notifications with variable binding
- **Delivery Tracking**: Events from 5 providers (delivered, bounced, opened, clicked, complained)
- **Analytics Dashboard**: Stats, daily charts, delivery rate
- **Suppression Management**: Hard bounces, complaints, manual suppressions

### Template Editor (Swappable)

The template editor is configurable via `config('filament-mail.template_editor.driver')`:

@verbatim
<code-snippet name="Configure template editor" lang="php">
// .env
FILAMENT_MAIL_EDITOR=rich_editor  // or 'unlayer'
UNLAYER_PROJECT_ID=your-project-id  // optional, for Unlayer

// config/filament-mail.php
'template_editor' => [
    'driver' => env('FILAMENT_MAIL_EDITOR', 'rich_editor'),
    'unlayer_project_id' => env('UNLAYER_PROJECT_ID'),
    'merge_tags' => [],
],
</code-snippet>
@endverbatim

### Sending Template-Based Notifications

@verbatim
<code-snippet name="MailNotification usage" lang="php">
use JeffersonGoncalves\FilamentMail\Notifications\MailNotification;

// Simple notification
$user->notify(new MailNotification(
    templateKey: 'auth.welcome',
    variables: ['name' => $user->name, 'login_url' => route('login')],
));

// With locale, cc, and attachments
$user->notify(new MailNotification(
    templateKey: 'transactional.invoice',
    variables: ['invoice_number' => $invoice->number, 'total' => $invoice->total],
    metadata: [
        'locale' => 'pt_BR',
        'cc' => ['finance@company.com'],
        'attachments' => [storage_path("invoices/{$invoice->number}.pdf")],
    ],
));

// Without notifiable (via Notification facade)
use Illuminate\Support\Facades\Notification;
Notification::route('mail', $email)->notify(
    new MailNotification('auth.reset-password', ['url' => $resetUrl])
);
</code-snippet>
@endverbatim

### HasMailTemplate Trait (for Mailables)

@verbatim
<code-snippet name="HasMailTemplate trait" lang="php">
use JeffersonGoncalves\FilamentMail\Traits\HasMailTemplate;
use Illuminate\Mail\Mailable;

class WelcomeMail extends Mailable
{
    use HasMailTemplate;

    public function __construct(User $user)
    {
        $this->templateKey = 'auth.welcome';
        $this->templateVariables = ['name' => $user->name];
    }

    public function build(): static
    {
        return $this->buildContent();
    }
}
</code-snippet>
@endverbatim

### Plugin Registration

@verbatim
<code-snippet name="Register plugin in Filament panel" lang="php">
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

public function panel(Panel $panel): Panel
{
    return $panel->plugins([
        FilamentMailPlugin::make()
            ->navigationGroup('Email')
            ->dashboard()
            ->tenantScoping(),
        SpatieTranslatablePlugin::make()
            ->defaultLocales(['en', 'pt_BR']),
    ]);
}
</code-snippet>
@endverbatim

### Best Practices

- Always use `MailNotification` for template-based emails instead of raw `Mail::send()`
- Use merge tags (`{{variable}}`) in templates for dynamic content
- Set `FILAMENT_MAIL_EDITOR=unlayer` for visual email design, `rich_editor` for simple HTML
- Publish the migration for `body_design` column when using Unlayer: `php artisan vendor:publish --tag="filament-mail-migrations"`
