---
name: filament-mail-development
description: Build and work with Filament Mail features, including template editors, MailNotification, delivery tracking, and analytics.
---

# Filament Mail Development

## When to use this skill

Use this skill when:
- Working with email template management in Filament
- Sending template-based notifications with variable binding
- Configuring the Unlayer visual email editor
- Creating custom template editor drivers
- Managing mail logs, suppressions, or delivery tracking
- Building analytics dashboards for email delivery

## Core Concepts

### Template Editor Contract

The package uses a swappable editor system via `TemplateEditorContract`:

```php
use JeffersonGoncalves\FilamentMail\Contracts\TemplateEditorContract;

interface TemplateEditorContract
{
    public function getFormField(string $fieldName = 'html_body'): Component;
    public function render(string $content, array $variables): string;
}
```

Two drivers are included:
- `RichEditorDriver` — Standard Filament RichEditor (default)
- `UnlayerEditorDriver` — Visual drag-and-drop editor via Unlayer CDN

Configure in `.env`:
```
FILAMENT_MAIL_EDITOR=rich_editor  # or 'unlayer'
UNLAYER_PROJECT_ID=your-project-id
```

### Creating a Custom Editor Driver

```php
use JeffersonGoncalves\FilamentMail\Contracts\TemplateEditorContract;

class MyCustomEditorDriver implements TemplateEditorContract
{
    public function getFormField(string $fieldName = 'html_body'): Component
    {
        return MyCustomField::make($fieldName)->columnSpanFull();
    }

    public function render(string $content, array $variables): string
    {
        // Replace {{variable}} and {{ variable }} patterns
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
```

Register in a service provider:
```php
$this->app->bind(TemplateEditorContract::class, MyCustomEditorDriver::class);
```

### MailNotification

Send emails using database templates with variable binding:

```php
use JeffersonGoncalves\FilamentMail\Notifications\MailNotification;

$user->notify(new MailNotification(
    templateKey: 'auth.welcome',
    variables: ['name' => $user->name, 'login_url' => route('login')],
    metadata: [
        'locale' => 'pt_BR',
        'cc' => ['admin@company.com'],
        'bcc' => ['archive@company.com'],
        'reply_to' => 'support@company.com',
        'attachments' => [storage_path('file.pdf')],
    ],
));
```

The notification:
1. Resolves the `MailTemplate` by key (must be `is_active = true`)
2. Uses the configured `TemplateEditorContract` to render variables in subject and body
3. Sends via the `filament-mail::emails.template` view

### HasMailTemplate Trait

For traditional Mailables:

```php
use JeffersonGoncalves\FilamentMail\Traits\HasMailTemplate;

class InvoiceMail extends Mailable
{
    use HasMailTemplate;

    public function __construct(Invoice $invoice)
    {
        $this->templateKey = 'billing.invoice';
        $this->templateVariables = [
            'number' => $invoice->number,
            'total' => $invoice->formatted_total,
        ];
        $this->templateLocale = 'pt_BR'; // optional
    }

    public function build(): static
    {
        return $this->buildContent();
    }
}
```

### Unlayer Editor

When using `FILAMENT_MAIL_EDITOR=unlayer`:
- The editor saves two fields: `html_body` (compiled HTML) and `body_design` (JSON design)
- Publish the migration: `php artisan vendor:publish --tag="filament-mail-migrations"`
- Configure merge tags in config for variable auto-completion in Unlayer

### Model Reference

The `MailTemplate` model (from `laravel-mail`) has translatable fields:
- `subject` — Email subject (translatable)
- `html_body` — HTML content (translatable)
- `text_body` — Plain text content (translatable)
- `body_design` — Unlayer JSON design (not translatable)
- `variables` — JSON array of `{name, type, example}`
- `key` — Unique template identifier (e.g., `auth.welcome`)

Use locale-aware getters:
```php
$template->getSubjectForLocale('pt_BR');
$template->getHtmlBodyForLocale('en');
```

## Common Patterns

### Pattern 1: Create Template + Send Notification

```php
// 1. Create template in Filament UI or via seeder
MailTemplate::create([
    'key' => 'order.confirmed',
    'name' => 'Order Confirmation',
    'subject' => 'Order #{{order_id}} confirmed',
    'html_body' => '<h1>Thank you, {{name}}!</h1><p>Order #{{order_id}} is confirmed.</p>',
    'is_active' => true,
]);

// 2. Send notification
$user->notify(new MailNotification('order.confirmed', [
    'name' => $user->name,
    'order_id' => $order->id,
]));
```

### Pattern 2: Multi-locale Templates

```php
$template = MailTemplate::create([
    'key' => 'auth.welcome',
    'name' => 'Welcome',
    'subject' => ['en' => 'Welcome!', 'pt_BR' => 'Bem-vindo!'],
    'html_body' => ['en' => '<h1>Welcome {{name}}</h1>', 'pt_BR' => '<h1>Bem-vindo {{name}}</h1>'],
    'is_active' => true,
]);

// Send in Portuguese
$user->notify(new MailNotification('auth.welcome', ['name' => $user->name], ['locale' => 'pt_BR']));
```

## Troubleshooting

### Error: Template not found

**Cause**: Template key doesn't exist or `is_active` is false.
**Solution**: Check `mail_templates` table for the key and ensure `is_active = true`.

### Error: body_design column not found

**Cause**: Migration not published when using Unlayer driver.
**Solution**: Run `php artisan vendor:publish --tag="filament-mail-migrations" && php artisan migrate`.
