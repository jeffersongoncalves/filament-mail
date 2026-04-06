<?php

declare(strict_types=1);

use JeffersonGoncalves\FilamentMail\Resources\MailLogResource;
use JeffersonGoncalves\LaravelMail\Models\MailLog;

it('can get the model class', function () {
    expect(MailLogResource::getModel())
        ->toBe(MailLog::class);
});

it('has correct navigation group', function () {
    expect(MailLogResource::getNavigationGroup())
        ->toBe('Email');
});

it('has correct model label', function () {
    expect(MailLogResource::getModelLabel())
        ->toBe('Mail Log');
});

it('has correct plural model label', function () {
    expect(MailLogResource::getPluralModelLabel())
        ->toBe('Mail Logs');
});

it('has list and view pages', function () {
    $pages = MailLogResource::getPages();

    expect($pages)->toHaveKeys(['index', 'view']);
});
