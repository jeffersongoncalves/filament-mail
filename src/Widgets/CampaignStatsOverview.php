<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use JeffersonGoncalves\FilamentMail\Pages\MailCampaigns;
use JeffersonGoncalves\LaravelMail\Campaigns\CampaignReport;

class CampaignStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $tag = MailCampaigns::selectedCampaign($this->filters);

        if ($tag === null) {
            return [];
        }

        $stats = app(CampaignReport::class)->stats($tag, MailCampaigns::periodStart($this->filters['period'] ?? null));
        $t = fn (string $key) => __("filament-mail::filament-mail.campaigns.{$key}");

        return [
            Stat::make($t('sent'), number_format($stats->sent))
                ->description($tag)
                ->descriptionIcon('heroicon-o-megaphone')
                ->color('primary'),
            Stat::make($t('delivered'), $stats->deliveryRate().'%')
                ->description(number_format($stats->delivered))
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make($t('opened'), $stats->openRate().'%')
                ->description(number_format($stats->opened))
                ->descriptionIcon('heroicon-o-envelope-open')
                ->color('info'),
            Stat::make($t('clicked'), $stats->clickRate().'%')
                ->description($t('click_to_open').': '.$stats->clickToOpenRate().'%')
                ->descriptionIcon('heroicon-o-cursor-arrow-rays')
                ->color('info'),
            Stat::make($t('bounced'), $stats->bounceRate().'%')
                ->description(number_format($stats->bounced))
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
            Stat::make($t('complained'), $stats->complaintRate().'%')
                ->description(number_format($stats->complained))
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('warning'),
        ];
    }
}
