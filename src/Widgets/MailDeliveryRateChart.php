<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\LaravelMail\Services\MailStats;

class MailDeliveryRateChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Delivery Rate';

    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $stats = app(MailStats::class);
        $days = (int) ($this->filters['period'] ?? 7);
        $from = Carbon::now()->subDays($days);
        $to = Carbon::now();

        $delivered = $stats->delivered($from, $to);
        $bounced = $stats->bounced($from, $to);
        $complained = $stats->complained($from, $to);
        $sent = $stats->sent($from, $to);
        $other = max(0, $sent - $delivered - $bounced - $complained);

        return [
            'datasets' => [
                [
                    'data' => [$delivered, $bounced, $complained, $other],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                        'rgb(245, 158, 11)',
                        'rgb(156, 163, 175)',
                    ],
                ],
            ],
            'labels' => ['Delivered', 'Bounced', 'Complained', 'Other'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
