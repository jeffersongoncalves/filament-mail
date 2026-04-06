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
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;
use JeffersonGoncalves\FilamentMail\Widgets\MailAnalyticsChart;
use JeffersonGoncalves\FilamentMail\Widgets\MailDeliveryRateChart;
use JeffersonGoncalves\FilamentMail\Widgets\MailStatsOverview;

class MailDashboard extends Page
{
    use HasFiltersForm;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Mail Dashboard';

    protected static ?string $slug = 'mail-dashboard';

    protected string $view = 'filament-mail::pages.mail-dashboard';

    public static function getNavigationGroup(): ?string
    {
        return FilamentMailPlugin::get()->getNavigationGroup();
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('period')
                ->label('Period')
                ->options([
                    '7' => 'Last 7 days',
                    '30' => 'Last 30 days',
                    '90' => 'Last 90 days',
                ])
                ->default('7'),
        ]);
    }

    /**
     * @return array<class-string<Widget>|WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            MailStatsOverview::class,
            MailAnalyticsChart::class,
            MailDeliveryRateChart::class,
        ];
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
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }
}
