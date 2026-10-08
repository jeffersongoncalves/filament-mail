<?php

declare(strict_types=1);

return [
    'navigation' => [
        'group' => 'Email',
        'mail_logs' => 'Mail Logs',
        'mail_templates' => 'Mail Templates',
        'suppressions' => 'Suppressions',
        'dashboard' => 'Mail Dashboard',
    ],

    'mail_log' => [
        'label' => 'Mail Log',
        'plural_label' => 'Mail Logs',
    ],

    'mail_template' => [
        'label' => 'Mail Template',
        'plural_label' => 'Mail Templates',
    ],

    'mail_suppression' => [
        'label' => 'Suppression',
        'plural_label' => 'Suppressions',
    ],

    'actions' => [
        'resend' => 'Resend',
        'retry' => 'Retry',
        'preview' => 'Preview',
        'send_test' => 'Send Test',
        'duplicate' => 'Duplicate',
        'unsuppress' => 'Unsuppress',
    ],

    'statuses' => [
        'pending' => 'Pending',
        'sent' => 'Sent',
        'delivered' => 'Delivered',
        'bounced' => 'Bounced',
        'complained' => 'Complained',
        'failed' => 'Failed',
    ],

    'widgets' => [
        'emails_sent' => 'Emails Sent',
        'delivered' => 'Delivered',
        'bounced' => 'Bounced',
        'opened' => 'Opened',
        'delivery_rate' => 'delivery rate',
        'bounce_rate' => 'bounce rate',
    ],

    'campaigns' => [
        'navigation' => 'Campaigns',
        'title' => 'Campaigns',
        'period' => 'Period',
        'last_days' => 'Last :days days',
        'all_time' => 'All time',
        'campaign' => 'Campaign',
        'latest_campaign' => 'Latest campaign',
        'all_campaigns' => 'All campaigns',
        'top_links' => 'Top links',
        'link' => 'Link',
        'clicks' => 'Clicks',
        'unique' => 'Unique',
        'sent' => 'Sent',
        'delivered' => 'Delivered',
        'opened' => 'Opened',
        'clicked' => 'Clicked',
        'click_to_open' => 'Click-to-open',
        'bounced' => 'Bounced',
        'complained' => 'Complained',
        'empty' => 'No tagged emails in this period.',
        'no_clicks' => 'No clicks yet.',
        'disabled' => 'Campaign tracking is off: set LARAVEL_MAIL_CAMPAIGNS_ENABLED=true and run the add_tags_to_mail_logs_table migration of laravel-mail.',
    ],
];
