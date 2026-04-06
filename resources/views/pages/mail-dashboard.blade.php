<x-filament-panels::page>
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <x-filament-widgets::widgets
        :columns="$this->getColumns()"
        :data="[
            ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
            ...$this->getWidgetData(),
        ]"
        :widgets="$this->getVisibleWidgets()"
    />

    <div class="fi-mail-dashboard-grid">
        {{-- Recent Emails --}}
        <x-filament::section>
            <x-slot name="heading">Recent Emails</x-slot>

            @php
                $modelClass = config('laravel-mail.models.mail_log', \JeffersonGoncalves\LaravelMail\Models\MailLog::class);
                $recentLogs = $modelClass::query()->latest()->limit(10)->get();
            @endphp

            <div class="fi-mail-table-wrap">
                <table class="fi-mail-table-sm">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Subject</th>
                            <th>To</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                            <tr>
                                <td>
                                    @php
                                        $statusColor = match($log->status->value) {
                                            'pending' => 'gray',
                                            'sent' => 'info',
                                            'delivered' => 'success',
                                            'bounced' => 'danger',
                                            'complained' => 'warning',
                                            'failed' => 'danger',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <x-filament::badge :color="$statusColor" size="sm">
                                        {{ ucfirst($log->status->value) }}
                                    </x-filament::badge>
                                </td>
                                <td>{{ Str::limit($log->subject, 40) }}</td>
                                <td>{{ $log->to[0]['email'] ?? '—' }}</td>
                                <td class="fi-mail-cell-muted">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="fi-mail-empty" style="text-align: center; padding: 1rem 0.75rem;">No emails found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        {{-- Recent Bounces & Complaints --}}
        <x-filament::section>
            <x-slot name="heading">Recent Bounces & Complaints</x-slot>

            @php
                $modelClass = config('laravel-mail.models.mail_log', \JeffersonGoncalves\LaravelMail\Models\MailLog::class);
                $recentIssues = $modelClass::query()
                    ->whereIn('status', [\JeffersonGoncalves\LaravelMail\Enums\MailStatus::Bounced, \JeffersonGoncalves\LaravelMail\Enums\MailStatus::Complained])
                    ->latest()
                    ->limit(5)
                    ->get();
            @endphp

            <div class="fi-mail-table-wrap">
                <table class="fi-mail-table-sm">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Subject</th>
                            <th>To</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentIssues as $log)
                            <tr>
                                <td>
                                    <x-filament::badge :color="$log->status->value === 'bounced' ? 'danger' : 'warning'" size="sm">
                                        {{ ucfirst($log->status->value) }}
                                    </x-filament::badge>
                                </td>
                                <td>{{ Str::limit($log->subject, 40) }}</td>
                                <td>{{ $log->to[0]['email'] ?? '—' }}</td>
                                <td class="fi-mail-cell-muted">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="fi-mail-empty" style="text-align: center; padding: 1rem 0.75rem;">No bounces or complaints.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
