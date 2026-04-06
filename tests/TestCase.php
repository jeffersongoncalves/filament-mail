<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Tests;

use Filament\FilamentServiceProvider;
use Filament\Support\SupportServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\FilamentMail\FilamentMailServiceProvider;
use JeffersonGoncalves\FilamentMail\Tests\Fixtures\TestPanelProvider;
use JeffersonGoncalves\LaravelMail\LaravelMailServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FilamentServiceProvider::class,
            TestPanelProvider::class,
            LaravelMailServiceProvider::class,
            FilamentMailServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('filament-mail.template_editor.locales', ['en', 'pt_BR']);
    }

    protected function setUpDatabase(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->default('');
            $table->timestamps();
        });

        Schema::create('mail_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('mailable_class')->nullable();
            $table->json('subject');
            $table->json('html_body');
            $table->json('text_body')->nullable();
            $table->json('body_design')->nullable();
            $table->json('variables')->nullable();
            $table->string('layout')->nullable();
            $table->string('tenant_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('mail_template_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mail_template_id')->constrained('mail_templates')->cascadeOnDelete();
            $table->integer('version_number');
            $table->json('subject');
            $table->json('html_body');
            $table->json('text_body')->nullable();
            $table->json('body_design')->nullable();
            $table->text('change_note')->nullable();
            $table->string('author')->nullable();
            $table->timestamps();
        });

        Schema::create('mail_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('status')->default('pending');
            $table->string('mailer')->nullable();
            $table->string('subject')->nullable();
            $table->json('from')->nullable();
            $table->json('to')->nullable();
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->json('reply_to')->nullable();
            $table->longText('html_body')->nullable();
            $table->longText('text_body')->nullable();
            $table->json('headers')->nullable();
            $table->json('attachments')->nullable();
            $table->json('metadata')->nullable();
            $table->string('provider_message_id')->nullable();
            $table->foreignUuid('mail_template_id')->nullable()->constrained('mail_templates')->nullOnDelete();
            $table->string('tenant_id')->nullable();
            $table->timestamps();
        });

        Schema::create('mail_suppressions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->string('reason')->default('manual');
            $table->string('provider')->nullable();
            $table->foreignUuid('mail_log_id')->nullable()->constrained('mail_logs')->nullOnDelete();
            $table->string('tenant_id')->nullable();
            $table->timestamp('suppressed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('mail_tracking_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mail_log_id')->constrained('mail_logs')->cascadeOnDelete();
            $table->string('type');
            $table->string('provider');
            $table->string('recipient')->nullable();
            $table->string('bounce_type')->nullable();
            $table->text('url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamps();
        });
    }
}
