<?php

declare(strict_types=1);

use JeffersonGoncalves\FilamentMail\Resources\MailSuppressionResource;
use JeffersonGoncalves\LaravelMail\Models\MailSuppression;

it('can get the model class', function () {
    expect(MailSuppressionResource::getModel())
        ->toBe(MailSuppression::class);
});

it('has correct navigation group', function () {
    expect(MailSuppressionResource::getNavigationGroup())
        ->toBe('Email');
});

it('has correct model label', function () {
    expect(MailSuppressionResource::getModelLabel())
        ->toBe('Suppression');
});

it('has correct plural model label', function () {
    expect(MailSuppressionResource::getPluralModelLabel())
        ->toBe('Suppressions');
});

it('has list and create pages', function () {
    $pages = MailSuppressionResource::getPages();

    expect($pages)->toHaveKeys(['index', 'create']);
});
