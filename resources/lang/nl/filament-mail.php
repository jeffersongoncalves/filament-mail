<?php

return [
    'navigation' => [
        'group' => 'E-mail',
        'mail_logs' => 'E-maillogs',
        'mail_templates' => 'E-mailsjablonen',
        'suppressions' => 'Blokkeringen',
        'dashboard' => 'E-maildashboard',
    ],
    'mail_log' => [
        'label' => 'E-maillog',
        'plural_label' => 'E-maillogs',
    ],
    'mail_template' => [
        'label' => 'E-mailsjabloon',
        'plural_label' => 'E-mailsjablonen',
    ],
    'mail_suppression' => [
        'label' => 'Blokkering',
        'plural_label' => 'Blokkeringen',
    ],
    'actions' => [
        'resend' => 'Opnieuw verzenden',
        'retry' => 'Opnieuw proberen',
        'preview' => 'Voorbeeld',
        'send_test' => 'Test verzenden',
        'duplicate' => 'Dupliceren',
        'unsuppress' => 'Blokkering opheffen',
    ],
    'statuses' => [
        'pending' => 'In afwachting',
        'sent' => 'Verzonden',
        'delivered' => 'Afgeleverd',
        'bounced' => 'Gebounced',
        'complained' => 'Klacht',
        'failed' => 'Mislukt',
    ],
    'widgets' => [
        'emails_sent' => 'Verzonden e-mails',
        'delivered' => 'Afgeleverd',
        'bounced' => 'Gebounced',
        'opened' => 'Geopend',
        'delivery_rate' => 'afleveringspercentage',
        'bounce_rate' => 'bouncepercentage',
    ],
];
