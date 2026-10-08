<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Illuminate\Foundation\Auth\User;
use JeffersonGoncalves\FilamentMail\FilamentMailPlugin;
use JeffersonGoncalves\FilamentMail\Pages\MailCampaigns;
use JeffersonGoncalves\FilamentMail\Widgets\CampaignStatsOverview;
use JeffersonGoncalves\LaravelMail\Enums\MailStatus;
use JeffersonGoncalves\LaravelMail\Enums\TrackingEventType;
use JeffersonGoncalves\LaravelMail\Enums\TrackingProvider;
use JeffersonGoncalves\LaravelMail\Models\MailLog;
use JeffersonGoncalves\LaravelMail\Models\MailTrackingEvent;
use Livewire\Livewire;

function campaignMail(string $tag, MailStatus $status = MailStatus::Delivered, ?string $clickedUrl = null): MailLog
{
    $log = MailLog::create([
        'subject' => 'Promo',
        'to' => [['email' => 'to@example.com', 'name' => '']],
        'status' => $status,
        'tags' => [$tag],
    ]);

    if ($clickedUrl !== null) {
        foreach ([TrackingEventType::Opened, TrackingEventType::Clicked] as $type) {
            MailTrackingEvent::create([
                'mail_log_id' => $log->id,
                'type' => $type,
                'provider' => TrackingProvider::Pixel,
                'url' => $type === TrackingEventType::Clicked ? $clickedUrl : null,
                'occurred_at' => now(),
            ]);
        }
    }

    return $log;
}

beforeEach(function () {
    config()->set('laravel-mail.campaigns.enabled', true);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs((new User)->forceFill(['id' => 1, 'name' => 'Admin', 'email' => 'admin@example.com']));
});

it('registers the campaigns page unless disabled', function () {
    expect(Filament::getPanel('admin')->getPages())->toContain(MailCampaigns::class)
        ->and(FilamentMailPlugin::make()->campaigns(false))->toBeInstanceOf(FilamentMailPlugin::class)
        ->and(MailCampaigns::getNavigationGroup())->toBe('Email')
        ->and(MailCampaigns::getNavigationLabel())->toBe('Campaigns');
});

it('lists the campaigns and the top links of the latest one', function () {
    campaignMail('welcome');
    $this->travel(1)->minutes();
    campaignMail('black-friday', clickedUrl: 'https://shop.test/deals');
    campaignMail('black-friday', MailStatus::Bounced);

    Livewire::test(MailCampaigns::class)
        ->assertSuccessful()
        ->assertSeeHtml('wire:click="$set(\'filters.campaign\', \'black-friday\')"')
        ->assertSee('welcome')
        ->assertSee('black-friday')
        ->assertSee('https://shop.test/deals')
        ->assertDontSee(__('filament-mail::filament-mail.campaigns.disabled'));
});

it('switches the selected campaign from the filter', function () {
    campaignMail('welcome', clickedUrl: 'https://shop.test/welcome');
    campaignMail('black-friday', clickedUrl: 'https://shop.test/deals');

    Livewire::test(MailCampaigns::class)
        ->set('filters.campaign', 'welcome')
        ->assertSee('https://shop.test/welcome')
        ->assertDontSee('https://shop.test/deals');
});

it('offers the campaigns of the period as filter options', function () {
    campaignMail('black-friday');
    MailLog::query()->update(['created_at' => now()->subDays(60)]);
    campaignMail('welcome');

    expect(MailCampaigns::campaignOptions('30'))->toBe(['welcome' => 'welcome'])
        ->and(MailCampaigns::campaignOptions('0'))->toHaveKeys(['welcome', 'black-friday']);
});

it('shows the campaign stats in the widget', function () {
    campaignMail('black-friday', clickedUrl: 'https://shop.test/deals');
    campaignMail('black-friday', MailStatus::Bounced);

    Livewire::test(CampaignStatsOverview::class, ['filters' => ['period' => '30', 'campaign' => 'black-friday']])
        ->assertSee('black-friday')
        ->assertSee('50%');
});

it('warns when campaign tracking is off', function () {
    config()->set('laravel-mail.campaigns.enabled', false);

    Livewire::test(MailCampaigns::class)
        ->assertSee(__('filament-mail::filament-mail.campaigns.disabled'))
        ->assertSee(__('filament-mail::filament-mail.campaigns.empty'));
});
