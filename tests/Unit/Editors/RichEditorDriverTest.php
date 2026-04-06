<?php

declare(strict_types=1);

use JeffersonGoncalves\FilamentMail\Editors\RichEditorDriver;

it('replaces variables without spaces', function () {
    $driver = new RichEditorDriver;
    $result = $driver->render('Hello {{name}}!', ['name' => 'John']);

    expect($result)->toBe('Hello John!');
});

it('replaces variables with spaces', function () {
    $driver = new RichEditorDriver;
    $result = $driver->render('Hello {{ name }}!', ['name' => 'Jane']);

    expect($result)->toBe('Hello Jane!');
});

it('replaces multiple variables', function () {
    $driver = new RichEditorDriver;
    $result = $driver->render(
        'Hi {{name}}, your order #{{ order_id }} is ready.',
        ['name' => 'Alice', 'order_id' => '12345']
    );

    expect($result)->toBe('Hi Alice, your order #12345 is ready.');
});

it('leaves unknown variables intact', function () {
    $driver = new RichEditorDriver;
    $result = $driver->render('Hello {{name}}, your role is {{role}}.', ['name' => 'Bob']);

    expect($result)->toBe('Hello Bob, your role is {{role}}.');
});

it('returns empty string for empty content', function () {
    $driver = new RichEditorDriver;
    $result = $driver->render('', ['name' => 'Test']);

    expect($result)->toBe('');
});
