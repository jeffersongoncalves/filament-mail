<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
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
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('filament-mail-styles', __DIR__.'/../resources/dist/filament-mail.css'),
        ], 'jeffersongoncalves/filament-mail');

        Livewire::component('filament-mail::mail-preview', MailPreview::class);
    }
}
