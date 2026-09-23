<?php

return [
    'navigation' => [
        'group' => 'Почта',
        'mail_logs' => 'Журнал писем',
        'mail_templates' => 'Шаблоны писем',
        'suppressions' => 'Блок-лист',
        'dashboard' => 'Панель почты',
    ],
    'mail_log' => [
        'label' => 'Запись журнала',
        'plural_label' => 'Журнал писем',
    ],
    'mail_template' => [
        'label' => 'Шаблон письма',
        'plural_label' => 'Шаблоны писем',
    ],
    'mail_suppression' => [
        'label' => 'Блокировка',
        'plural_label' => 'Блок-лист',
    ],
    'actions' => [
        'resend' => 'Отправить повторно',
        'retry' => 'Повторить',
        'preview' => 'Предпросмотр',
        'send_test' => 'Отправить тест',
        'duplicate' => 'Дублировать',
        'unsuppress' => 'Снять блокировку',
    ],
    'statuses' => [
        'pending' => 'В ожидании',
        'sent' => 'Отправлено',
        'delivered' => 'Доставлено',
        'bounced' => 'Возврат',
        'complained' => 'Жалоба',
        'failed' => 'Ошибка',
    ],
    'widgets' => [
        'emails_sent' => 'Отправлено писем',
        'delivered' => 'Доставлено',
        'bounced' => 'Возвраты',
        'opened' => 'Открыто',
        'delivery_rate' => 'доля доставки',
        'bounce_rate' => 'доля возвратов',
    ],
];
