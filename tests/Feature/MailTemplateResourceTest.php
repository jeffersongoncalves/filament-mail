<?php

declare(strict_types=1);

use JeffersonGoncalves\FilamentMail\Resources\MailTemplateResource;
use JeffersonGoncalves\LaravelMail\Models\MailTemplate;

it('can get the model class', function () {
    expect(MailTemplateResource::getModel())
        ->toBe(MailTemplate::class);
});

it('has correct navigation group', function () {
    expect(MailTemplateResource::getNavigationGroup())
        ->toBe('Email');
});

it('has correct model label', function () {
    expect(MailTemplateResource::getModelLabel())
        ->toBe('Mail Template');
});

it('has correct plural model label', function () {
    expect(MailTemplateResource::getPluralModelLabel())
        ->toBe('Mail Templates');
});

it('has list, create, edit, and view pages', function () {
    $pages = MailTemplateResource::getPages();

    expect($pages)->toHaveKeys(['index', 'create', 'edit', 'view']);
});
