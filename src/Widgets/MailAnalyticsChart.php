<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\LaravelMail\Services\MailStats;

class MailAnalyticsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Email Analytics';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $stats = app(MailStats::class);
        $days = (int) ($this->filters['period'] ?? 7);
        $from = Carbon::now()->subDays($days);
        $to = Carbon::now();

        $dailyStats = $stats->dailyStats($from, $to);

        return [
            'datasets' => [
                [
                    'label' => 'Sent',
                    'data' => $dailyStats->pluck('sent')->all(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Delivered',
                    'data' => $dailyStats->pluck('delivered')->all(),
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Bounced',
                    'data' => $dailyStats->pluck('bounced')->all(),
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $dailyStats->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('M d'))->all(),
        ];
    }
}
