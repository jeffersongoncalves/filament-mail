<?php

declare(strict_types=1);
use JeffersonGoncalves\FilamentMail\Resources\MailLog\MailLogResource;
use JeffersonGoncalves\FilamentMail\Resources\MailSuppression\MailSuppressionResource;
use JeffersonGoncalves\FilamentMail\Resources\MailTemplate\MailTemplateResource;

return [

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | Configure which resources are enabled and their classes.
    | You can extend the default resources by providing your own classes.
    |
    */

    'resources' => [
        'mail_log' => [
            'enabled' => true,
            'class' => MailLogResource::class,
            'label' => 'Mail Log',
            'plural_label' => 'Mail Logs',
        ],
        'mail_template' => [
            'enabled' => true,
            'class' => MailTemplateResource::class,
            'label' => 'Mail Template',
            'plural_label' => 'Mail Templates',
        ],
        'mail_suppression' => [
            'enabled' => true,
            'class' => MailSuppressionResource::class,
            'label' => 'Suppression',
            'plural_label' => 'Suppressions',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | Enable or disable individual widgets.
    |
    */

    'widgets' => [
        'stats_overview' => true,
        'analytics_chart' => true,
        'delivery_rate_chart' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Page
    |--------------------------------------------------------------------------
    |
    | Enable the dedicated mail dashboard page with stats and charts.
    |
    */

    'dashboard' => [
        'enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    |
    | Customize the navigation group, icon, and sort order.
    |
    */

    'navigation' => [
        'group' => 'Email',
        'icon' => 'heroicon-o-envelope',
        'sort' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Template Editor
    |--------------------------------------------------------------------------
    |
    | Configure the template editor behavior and available locales.
    |
    */

    'template_editor' => [
        'driver' => env('FILAMENT_MAIL_EDITOR', 'rich_editor'),
        'locales' => ['en'],
        'default_locale' => 'en',
        'unlayer_project_id' => env('UNLAYER_PROJECT_ID'),
        'merge_tags' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preview
    |--------------------------------------------------------------------------
    |
    | Configure the email preview behavior.
    |
    */

    'preview' => [
        'max_width' => '800px',
        'sandbox' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tenant Scoping
    |--------------------------------------------------------------------------
    |
    | When enabled, all queries will be scoped to the current tenant.
    |
    */

    'tenant_scoping' => false,

];
