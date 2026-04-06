<div class="filament-hidden">

![Filament Mail](https://raw.githubusercontent.com/jeffersongoncalves/filament-mail/1.x/art/jeffersongoncalves-filament-mail.png)

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
        'locales' => ['en'],
        'default_locale' => 'en',
    ],

    'preview' => [
        'max_width' => '800px',
        'sandbox' => true,
    ],

    'tenant_scoping' => false,
];
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
