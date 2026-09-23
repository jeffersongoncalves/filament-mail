<?php

return [
    'navigation' => [
        'group' => 'メール',
        'mail_logs' => 'メールログ',
        'mail_templates' => 'メールテンプレート',
        'suppressions' => '配信停止リスト',
        'dashboard' => 'メールダッシュボード',
    ],
    'mail_log' => [
        'label' => 'メールログ',
        'plural_label' => 'メールログ',
    ],
    'mail_template' => [
        'label' => 'メールテンプレート',
        'plural_label' => 'メールテンプレート',
    ],
    'mail_suppression' => [
        'label' => '配信停止',
        'plural_label' => '配信停止リスト',
    ],
    'actions' => [
        'resend' => '再送信',
        'retry' => '再試行',
        'preview' => 'プレビュー',
        'send_test' => 'テスト送信',
        'duplicate' => '複製',
        'unsuppress' => '配信停止を解除',
    ],
    'statuses' => [
        'pending' => '保留中',
        'sent' => '送信済み',
        'delivered' => '配信済み',
        'bounced' => 'バウンス',
        'complained' => '苦情',
        'failed' => '失敗',
    ],
    'widgets' => [
        'emails_sent' => '送信済みメール',
        'delivered' => '配信済み',
        'bounced' => 'バウンス',
        'opened' => '開封済み',
        'delivery_rate' => '配信率',
        'bounce_rate' => 'バウンス率',
    ],
];
