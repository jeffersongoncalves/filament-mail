<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\Messages\MailMessage;
use JeffersonGoncalves\FilamentMail\Notifications\MailNotification;
use JeffersonGoncalves\LaravelMail\Models\MailTemplate;

beforeEach(function () {
    MailTemplate::create([
        'key' => 'auth.welcome',
        'name' => 'Welcome Email',
        'subject' => 'Welcome {{name}}!',
        'html_body' => '<h1>Hello {{ name }}</h1><p>Welcome to {{ app_name }}.</p>',
        'text_body' => 'Hello {{ name }}, welcome to {{ app_name }}.',
        'is_active' => true,
    ]);
});

it('resolves template by key', function () {
    $notification = new MailNotification('auth.welcome', ['name' => 'John', 'app_name' => 'TestApp']);
    $notifiable = new class
    {
        public function routeNotificationForMail(): string
        {
            return 'john@example.com';
        }
    };

    $message = $notification->toMail($notifiable);

    expect($message)->toBeInstanceOf(MailMessage::class);
});

it('replaces variables in subject and body', function () {
    $notification = new MailNotification('auth.welcome', ['name' => 'Alice', 'app_name' => 'MyApp']);
    $notifiable = new class
    {
        public function routeNotificationForMail(): string
        {
            return 'alice@example.com';
        }
    };

    $message = $notification->toMail($notifiable);

    expect($message->subject)->toBe('Welcome Alice!');
});

it('throws ModelNotFoundException for unknown key', function () {
    $notification = new MailNotification('nonexistent.key');
    $notifiable = new class
    {
        public function routeNotificationForMail(): string
        {
            return 'test@example.com';
        }
    };

    $notification->toMail($notifiable);
})->throws(ModelNotFoundException::class);

it('skips inactive templates', function () {
    MailTemplate::where('key', 'auth.welcome')->update(['is_active' => false]);

    $notification = new MailNotification('auth.welcome');
    $notifiable = new class
    {
        public function routeNotificationForMail(): string
        {
            return 'test@example.com';
        }
    };

    $notification->toMail($notifiable);
})->throws(ModelNotFoundException::class);

it('adds cc and bcc from metadata', function () {
    $notification = new MailNotification(
        'auth.welcome',
        ['name' => 'Bob', 'app_name' => 'TestApp'],
        [
            'cc' => ['cc@example.com'],
            'bcc' => ['bcc@example.com'],
        ]
    );
    $notifiable = new class
    {
        public function routeNotificationForMail(): string
        {
            return 'bob@example.com';
        }
    };

    $message = $notification->toMail($notifiable);

    expect($message->cc)->not->toBeEmpty();
    expect($message->bcc)->not->toBeEmpty();
});

it('adds reply_to from metadata', function () {
    $notification = new MailNotification(
        'auth.welcome',
        ['name' => 'Charlie', 'app_name' => 'TestApp'],
        ['reply_to' => 'reply@example.com']
    );
    $notifiable = new class
    {
        public function routeNotificationForMail(): string
        {
            return 'charlie@example.com';
        }
    };

    $message = $notification->toMail($notifiable);

    expect($message->replyTo)->not->toBeEmpty();
});

it('returns mail via channel', function () {
    $notification = new MailNotification('auth.welcome');
    $notifiable = new class
    {
        public function routeNotificationForMail(): string
        {
            return 'test@example.com';
        }
    };

    expect($notification->via($notifiable))->toBe(['mail']);
});
