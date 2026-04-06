<div class="filament-hidden">

![Filament Mail](https://raw.githubusercontent.com/jeffersongoncalves/filament-mail/1.x/art/jeffersongoncalves-filament-mail.jpg)

</div>

# Filament Mail

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-mail.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-mail)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-mail/tests.yml?branch=1.x&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/filament-mail/actions)
[![GitHub Code Style Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-mail/pint.yml?branch=1.x&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/filament-mail/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-mail.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-mail)

Complete email management UI for Filament. Built on top of [jeffersongoncalves/laravel-mail](https://github.com/jeffersongoncalves/laravel-mail), it provides a rich interface for managing email logs, database templates with multi-locale editing, delivery tracking, analytics dashboard, and suppression management.

## Compatibility

| Package | Filament | Laravel | PHP |
|---------|----------|---------|-----|
| [1.x](https://github.com/jeffersongoncalves/filament-mail/tree/1.x) | 3.x | 10+ | 8.1+ |
| [2.x](https://github.com/jeffersongoncalves/filament-mail/tree/2.x) | 4.x | 11+ | 8.2+ |
| [3.x](https://github.com/jeffersongoncalves/filament-mail/tree/3.x) | 5.x | 11+ | 8.2+ |

## Features

- **Mail Logs** — Browse, search, and view all sent emails with HTML preview, attachments, headers, and metadata
- **Resend & Retry** — Resend any email or retry failed deliveries directly from the UI
- **Mail Templates** — Create and edit database-driven email templates with multi-locale support (via spatie/laravel-translatable)
- **Swappable Template Editor** — Use Filament RichEditor (default) or Unlayer visual drag-and-drop editor
- **MailNotification** — Send template-based notifications with variable binding, cc, bcc, attachments
- **Template Versioning** — Automatic version history with change tracking
- **Live Preview** — Preview rendered templates with example data in an iframe sandbox
- **Send Test Email** — Send test emails from any template with locale selection
- **Delivery Tracking** — View tracking events (delivered, bounced, opened, clicked, complained) from 5 providers
- **Analytics Dashboard** — Stats overview, daily analytics chart, and delivery rate chart with period filters
- **Suppression Management** — Manage suppressed emails (hard bounces, complaints, manual suppressions)
- **Multi-tenant** — Optional tenant scoping for all queries
- **Configurable** — Disable/enable individual resources, widgets, and pages

## Installation

```bash
composer require jeffersongoncalves/filament-mail
```

The package requires [jeffersongoncalves/laravel-mail](https://github.com/jeffersongoncalves/laravel-mail) as a dependency. Make sure to run its migrations first:

```bash
php artisan vendor:publish --tag="laravel-mail-migrations"
php artisan migrate
```

Optionally, publish the config file:

```bash
php artisan vendor:publish --tag="filament-mail-config"
```

## Usage

Register the plugin in your Filament panel provider:

```php
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentMailPlugin::make(),
        ]);
}
```

### Translatable Templates (Multi-locale)

Mail templates use [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) for multi-locale support. The plugin integrates with Filament's official [Spatie Laravel Translatable Plugin](https://filamentphp.com/plugins/filament-spatie-laravel-translatable-plugin) to provide a locale switcher in the UI.

Register the `SpatieLaravelTranslatablePlugin` alongside `FilamentMailPlugin` in your panel:

```php
use Filament\SpatieLaravelTranslatablePlugin;
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            SpatieLaravelTranslatablePlugin::make()
                ->defaultLocales(['en', 'pt_BR', 'es']),
            FilamentMailPlugin::make(),
        ]);
}
```

The `subject`, `html_body`, and `text_body` fields on mail templates are translatable. Use the locale switcher in the header to switch between locales when creating or editing templates.

### Customization

```php
FilamentMailPlugin::make()
    ->navigationGroup('Email')
    ->navigationIcon('heroicon-o-envelope')
    ->navigationSort(50)
    ->mailLogResource()           // Enable/disable mail log resource
    ->mailTemplateResource()      // Enable/disable template resource
    ->mailSuppressionResource()   // Enable/disable suppression resource
    ->statsWidgets()              // Enable/disable stats widgets
    ->analyticsWidget()           // Enable/disable analytics charts
    ->dashboard()                 // Enable/disable dashboard page
    ->tenantScoping()             // Enable/disable tenant scoping
```

### Disabling Features

```php
FilamentMailPlugin::make()
    ->mailSuppressionResource(false)  // Disable suppression management
    ->analyticsWidget(false)          // Disable analytics charts
    ->dashboard(false)                // Disable dashboard page
```

## Configuration

```php
// config/filament-mail.php

return [
    'resources' => [
        'mail_log' => [
            'enabled' => true,
            'label' => 'Mail Log',
            'plural_label' => 'Mail Logs',
        ],
        'mail_template' => [
            'enabled' => true,
            'label' => 'Mail Template',
            'plural_label' => 'Mail Templates',
        ],
        'mail_suppression' => [
            'enabled' => true,
            'label' => 'Suppression',
            'plural_label' => 'Suppressions',
        ],
    ],

    'widgets' => [
        'stats_overview' => true,
        'analytics_chart' => true,
        'delivery_rate_chart' => true,
    ],

    'dashboard' => [
        'enabled' => true,
    ],

    'navigation' => [
        'group' => 'Email',
        'icon' => 'heroicon-o-envelope',
        'sort' => 50,
    ],

    'template_editor' => [
        'driver' => env('FILAMENT_MAIL_EDITOR', 'rich_editor'),
        'locales' => ['en'],
        'default_locale' => 'en',
        'unlayer_project_id' => env('UNLAYER_PROJECT_ID'),
        'merge_tags' => [],
    ],

    'preview' => [
        'max_width' => '800px',
        'sandbox' => true,
    ],

    'tenant_scoping' => false,
];
```

## Template Editor

The template editor is swappable via config. Set the `FILAMENT_MAIL_EDITOR` environment variable:

```env
# Default: standard Filament RichEditor
FILAMENT_MAIL_EDITOR=rich_editor

# Visual drag-and-drop editor via Unlayer
FILAMENT_MAIL_EDITOR=unlayer
UNLAYER_PROJECT_ID=your-project-id
```

When using Unlayer, publish and run the migration for the `body_design` column:

```bash
php artisan vendor:publish --tag="filament-mail-migrations"
php artisan migrate
```

### Creating a Custom Editor Driver

Implement `TemplateEditorContract` and bind it in a service provider:

```php
use JeffersonGoncalves\FilamentMail\Contracts\TemplateEditorContract;

class MyEditorDriver implements TemplateEditorContract
{
    public function getFormField(string $fieldName = 'html_body'): Component
    {
        return MyCustomField::make($fieldName)->columnSpanFull();
    }

    public function render(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $content = str_replace(
                ['{{' . $key . '}}', '{{ ' . $key . ' }}'],
                (string) $value,
                $content
            );
        }
        return $content;
    }
}

// In a service provider:
$this->app->bind(TemplateEditorContract::class, MyEditorDriver::class);
```

## MailNotification

Send emails using database templates with variable binding:

```php
use JeffersonGoncalves\FilamentMail\Notifications\MailNotification;

// Simple notification
$user->notify(new MailNotification(
    templateKey: 'auth.welcome',
    variables: [
        'name' => $user->name,
        'login_url' => route('login'),
    ],
));

// With locale, cc, and attachments
$user->notify(new MailNotification(
    templateKey: 'transactional.invoice',
    variables: [
        'invoice_number' => $invoice->number,
        'total' => number_format($invoice->total, 2, ',', '.'),
        'due_date' => $invoice->due_date->format('d/m/Y'),
    ],
    metadata: [
        'locale' => 'pt_BR',
        'cc' => ['finance@company.com'],
        'attachments' => [storage_path("invoices/{$invoice->number}.pdf")],
    ],
));

// Without a notifiable (via Notification facade)
use Illuminate\Support\Facades\Notification;

Notification::route('mail', $email)->notify(
    new MailNotification('auth.reset-password', ['url' => $resetUrl])
);
```

### HasMailTemplate Trait

For traditional Mailables, use the `HasMailTemplate` trait:

```php
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
```

## Extending Resources

You can extend the default resources by creating your own classes and updating the config:

```php
// config/filament-mail.php
'resources' => [
    'mail_log' => [
        'class' => App\Filament\Resources\CustomMailLogResource::class,
    ],
],
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
