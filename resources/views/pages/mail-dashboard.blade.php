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

    <div class="grid gap-6 md:grid-cols-2">
        {{-- Recent Emails --}}
        <x-filament::section>
            <x-slot name="heading">Recent Emails</x-slot>

            @php
                $modelClass = config('laravel-mail.models.mail_log', \JeffersonGoncalves\LaravelMail\Models\MailLog::class);
                $recentLogs = $modelClass::query()->latest()->limit(10)->get();
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Subject</th>
                            <th class="px-3 py-2">To</th>
                            <th class="px-3 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-3 py-2">
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
                                <td class="px-3 py-2">{{ Str::limit($log->subject, 40) }}</td>
                                <td class="px-3 py-2">{{ $log->to[0]['email'] ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-gray-500 italic">No emails found.</td>
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

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Subject</th>
                            <th class="px-3 py-2">To</th>
                            <th class="px-3 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentIssues as $log)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-3 py-2">
                                    <x-filament::badge :color="$log->status->value === 'bounced' ? 'danger' : 'warning'" size="sm">
                                        {{ ucfirst($log->status->value) }}
                                    </x-filament::badge>
                                </td>
                                <td class="px-3 py-2">{{ Str::limit($log->subject, 40) }}</td>
                                <td class="px-3 py-2">{{ $log->to[0]['email'] ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-gray-500 italic">No bounces or complaints.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
