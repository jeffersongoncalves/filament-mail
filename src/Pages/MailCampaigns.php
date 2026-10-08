<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Pages;

use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;
use JeffersonGoncalves\FilamentMail\Widgets\CampaignStatsOverview;
use JeffersonGoncalves\LaravelMail\Campaigns\CampaignReport;
use JeffersonGoncalves\LaravelMail\Campaigns\CampaignStats;

/**
 * Per-campaign numbers from laravel-mail's CampaignReport: a campaign is a mail tag.
 */
class MailCampaigns extends Page
{
    use HasFiltersForm;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'mail-campaigns';

    protected string $view = 'filament-mail::pages.mail-campaigns';

    public static function getNavigationGroup(): ?string
    {
        return FilamentMailPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-mail::filament-mail.campaigns.navigation');
    }

    public function getTitle(): string
    {
        return __('filament-mail::filament-mail.campaigns.title');
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->columns(2)->components(self::filterComponents());
    }

    /**
     * @return array<int, Select>
     */
    public static function filterComponents(): array
    {
        return [
            Select::make('period')
                ->label(__('filament-mail::filament-mail.campaigns.period'))
                ->options([
                    '7' => __('filament-mail::filament-mail.campaigns.last_days', ['days' => 7]),
                    '30' => __('filament-mail::filament-mail.campaigns.last_days', ['days' => 30]),
                    '90' => __('filament-mail::filament-mail.campaigns.last_days', ['days' => 90]),
                    '0' => __('filament-mail::filament-mail.campaigns.all_time'),
                ])
                ->default('30')
                ->live(),
            Select::make('campaign')
                ->label(__('filament-mail::filament-mail.campaigns.campaign'))
                ->placeholder(__('filament-mail::filament-mail.campaigns.latest_campaign'))
                ->options(fn (callable $get) => self::campaignOptions($get('period')))
                ->searchable()
                ->live(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function campaignOptions(mixed $period): array
    {
        $tags = app(CampaignReport::class)->tags(self::periodStart($period));

        return array_combine($tags, $tags);
    }

    public static function periodStart(mixed $period): ?Carbon
    {
        $days = (int) ($period ?? 30);

        return $days > 0 ? Carbon::now()->subDays($days) : null;
    }

    /**
     * The campaign the stats widget and the links table show: the selected one, or the most recent.
     */
    public static function selectedCampaign(?array $filters): ?string
    {
        $selected = $filters['campaign'] ?? null;

        if (filled($selected)) {
            return (string) $selected;
        }

        return app(CampaignReport::class)->tags(self::periodStart($filters['period'] ?? null))[0] ?? null;
    }

    /**
     * @return list<CampaignStats>
     */
    public function getCampaigns(): array
    {
        return app(CampaignReport::class)->all(self::periodStart($this->filters['period'] ?? null));
    }

    public function getSelectedStats(): ?CampaignStats
    {
        $tag = self::selectedCampaign($this->filters);

        return $tag === null ? null : app(CampaignReport::class)->stats($tag, self::periodStart($this->filters['period'] ?? null));
    }

    public function isTrackingEnabled(): bool
    {
        return (bool) config('laravel-mail.campaigns.enabled', false);
    }

    /**
     * @return array<class-string<Widget>|WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [CampaignStatsOverview::class];
    }

    /**
     * @return array<class-string<Widget>|WidgetConfiguration>
     */
    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    /**
     * @return int|string|array<string, int|string|null>
     */
    public function getColumns(): int|string|array
    {
        return 1;
    }
}
