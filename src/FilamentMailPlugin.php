<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\FilamentMail\Pages\MailDashboard;
use JeffersonGoncalves\FilamentMail\Resources\MailLog\MailLogResource;
use JeffersonGoncalves\FilamentMail\Resources\MailSuppression\MailSuppressionResource;
use JeffersonGoncalves\FilamentMail\Resources\MailTemplate\MailTemplateResource;
use JeffersonGoncalves\FilamentMail\Widgets\MailAnalyticsChart;
use JeffersonGoncalves\FilamentMail\Widgets\MailDeliveryRateChart;
use JeffersonGoncalves\FilamentMail\Widgets\MailStatsOverview;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

class FilamentMailPlugin implements Plugin
{
    protected bool|Closure $hasMailLogResource = true;

    protected bool|Closure $hasMailTemplateResource = true;

    protected bool|Closure $hasMailSuppressionResource = true;

    protected bool|Closure $hasStatsWidgets = true;

    protected bool|Closure $hasAnalyticsWidget = true;

    protected bool|Closure $hasDashboard = true;

    protected string|Closure $navigationGroup = 'Email';

    protected string $navigationIcon = 'heroicon-o-envelope';

    protected int $navigationSort = 50;

    protected bool|Closure $hasTenantScoping = false;

    public function getId(): string
    {
        return 'filament-mail';
    }

    public function register(Panel $panel): void
    {
        $resources = [];
        $pages = [];
        $widgets = [];

        if ($this->evaluate($this->hasMailLogResource) && config('filament-mail.resources.mail_log.enabled', true)) {
            $resources[] = config('filament-mail.resources.mail_log.class', MailLogResource::class);
        }

        if ($this->evaluate($this->hasMailTemplateResource) && config('filament-mail.resources.mail_template.enabled', true)) {
            $resources[] = config('filament-mail.resources.mail_template.class', MailTemplateResource::class);
        }

        if ($this->evaluate($this->hasMailSuppressionResource) && config('filament-mail.resources.mail_suppression.enabled', true)) {
            $resources[] = config('filament-mail.resources.mail_suppression.class', MailSuppressionResource::class);
        }

        if ($this->evaluate($this->hasDashboard) && config('filament-mail.dashboard.enabled', true)) {
            $pages[] = MailDashboard::class;
        }

        if ($this->evaluate($this->hasStatsWidgets) && config('filament-mail.widgets.stats_overview', true)) {
            $widgets[] = MailStatsOverview::class;
        }

        if ($this->evaluate($this->hasAnalyticsWidget)) {
            if (config('filament-mail.widgets.analytics_chart', true)) {
                $widgets[] = MailAnalyticsChart::class;
            }
            if (config('filament-mail.widgets.delivery_rate_chart', true)) {
                $widgets[] = MailDeliveryRateChart::class;
            }
        }

        if (! $panel->hasPlugin('spatie-translatable')) {
            $panel->plugin(
                SpatieTranslatablePlugin::make()
                    ->defaultLocales(config('filament-mail.template_editor.locales', ['en']))
            );
        }

        $panel
            ->resources($resources)
            ->pages($pages)
            ->widgets($widgets);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function mailLogResource(bool|Closure $condition = true): static
    {
        $this->hasMailLogResource = $condition;

        return $this;
    }

    public function mailTemplateResource(bool|Closure $condition = true): static
    {
        $this->hasMailTemplateResource = $condition;

        return $this;
    }

    public function mailSuppressionResource(bool|Closure $condition = true): static
    {
        $this->hasMailSuppressionResource = $condition;

        return $this;
    }

    public function statsWidgets(bool|Closure $condition = true): static
    {
        $this->hasStatsWidgets = $condition;

        return $this;
    }

    public function analyticsWidget(bool|Closure $condition = true): static
    {
        $this->hasAnalyticsWidget = $condition;

        return $this;
    }

    public function dashboard(bool|Closure $condition = true): static
    {
        $this->hasDashboard = $condition;

        return $this;
    }

    public function navigationGroup(string|Closure $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function navigationIcon(string $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function navigationSort(int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function tenantScoping(bool|Closure $condition = true): static
    {
        $this->hasTenantScoping = $condition;

        return $this;
    }

    public function getNavigationGroup(): string
    {
        return $this->evaluate($this->navigationGroup);
    }

    public function getNavigationIcon(): string
    {
        return $this->navigationIcon;
    }

    public function getNavigationSort(): int
    {
        return $this->navigationSort;
    }

    public function hasTenantScoping(): bool
    {
        return $this->evaluate($this->hasTenantScoping) || config('filament-mail.tenant_scoping', false);
    }

    protected function evaluate(bool|Closure|string $value): bool|string
    {
        if ($value instanceof Closure) {
            return $value();
        }

        return $value;
    }
}
