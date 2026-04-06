<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use JeffersonGoncalves\FilamentMail\Contracts\TemplateEditorContract;
use JeffersonGoncalves\FilamentMail\Editors\RichEditorDriver;
use JeffersonGoncalves\FilamentMail\Editors\UnlayerEditorDriver;
use JeffersonGoncalves\FilamentMail\Livewire\MailPreview;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMailServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-mail';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigration('add_body_design_to_mail_templates_table');
    }

    public function register(): void
    {
        parent::register();

        $this->app->bind(TemplateEditorContract::class, function () {
            return match (config('filament-mail.template_editor.driver')) {
                'unlayer' => new UnlayerEditorDriver,
                default => new RichEditorDriver,
            };
        });
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('filament-mail-styles', __DIR__.'/../resources/dist/filament-mail.css'),
        ], 'jeffersongoncalves/filament-mail');

        Livewire::component('filament-mail::mail-preview', MailPreview::class);
    }
}
