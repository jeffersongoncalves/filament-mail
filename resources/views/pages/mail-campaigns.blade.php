<x-filament-panels::page>
    @unless ($this->isTrackingEnabled())
        <x-filament::section>
            {{ __('filament-mail::filament-mail.campaigns.disabled') }}
        </x-filament::section>
    @endunless

    {{ $this->filtersForm }}

    <x-filament-widgets::widgets
        :columns="$this->getColumns()"
        :data="['filters' => $this->filters, ...$this->getWidgetData()]"
        :widgets="$this->getVisibleWidgets()"
    />

    @php
        $campaigns = $this->getCampaigns();
        $selected = $this->getSelectedStats();
    @endphp

    <div class="fi-mail-dashboard-grid">
        <x-filament::section>
            <x-slot name="heading">{{ __('filament-mail::filament-mail.campaigns.all_campaigns') }}</x-slot>

            <div class="fi-mail-table-wrap">
                <table class="fi-mail-table-sm">
                    <thead>
                        <tr>
                            <th>{{ __('filament-mail::filament-mail.campaigns.campaign') }}</th>
                            <th>{{ __('filament-mail::filament-mail.campaigns.sent') }}</th>
                            <th>{{ __('filament-mail::filament-mail.campaigns.opened') }}</th>
                            <th>{{ __('filament-mail::filament-mail.campaigns.clicked') }}</th>
                            <th>{{ __('filament-mail::filament-mail.campaigns.bounced') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($campaigns as $campaign)
                            <tr>
                                <td>
                                    <x-filament::link tag="button" wire:click="$set('filters.campaign', {{ \Illuminate\Support\Js::from($campaign->tag) }})">
                                        {{ $campaign->tag }}
                                    </x-filament::link>
                                </td>
                                <td>{{ number_format($campaign->sent) }}</td>
                                <td>{{ $campaign->openRate() }}%</td>
                                <td>{{ $campaign->clickRate() }}%</td>
                                <td>{{ $campaign->bounceRate() }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="fi-mail-empty" style="text-align: center; padding: 1rem 0.75rem;">{{ __('filament-mail::filament-mail.campaigns.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                {{ __('filament-mail::filament-mail.campaigns.top_links') }}@if ($selected) · {{ $selected->tag }}@endif
            </x-slot>

            <div class="fi-mail-table-wrap">
                <table class="fi-mail-table-sm">
                    <thead>
                        <tr>
                            <th>{{ __('filament-mail::filament-mail.campaigns.link') }}</th>
                            <th>{{ __('filament-mail::filament-mail.campaigns.clicks') }}</th>
                            <th>{{ __('filament-mail::filament-mail.campaigns.unique') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (array_slice($selected?->links ?? [], 0, 10, true) as $url => $link)
                            <tr>
                                <td style="word-break: break-all;">{{ $url }}</td>
                                <td>{{ number_format($link['clicks']) }}</td>
                                <td>{{ number_format($link['unique']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="fi-mail-empty" style="text-align: center; padding: 1rem 0.75rem;">{{ __('filament-mail::filament-mail.campaigns.no_clicks') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
