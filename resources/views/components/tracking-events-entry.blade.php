@php
    $events = $getRecord()->trackingEvents()->orderBy('occurred_at', 'desc')->get();
@endphp

<div>
    @if($events->isNotEmpty())
        <div class="fi-mail-table-wrap">
            <table class="fi-mail-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Provider</th>
                        <th>Recipient</th>
                        <th>Bounce Type</th>
                        <th>URL</th>
                        <th>Occurred At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>
                                @php
                                    $typeColor = match($event->type->value) {
                                        'delivered' => 'success',
                                        'bounced' => 'danger',
                                        'opened' => 'info',
                                        'clicked' => 'primary',
                                        'complained' => 'warning',
                                        'deferred' => 'gray',
                                        default => 'gray',
                                    };
                                @endphp
                                <x-filament::badge :color="$typeColor">
                                    {{ ucfirst($event->type->value) }}
                                </x-filament::badge>
                            </td>
                            <td>
                                <x-filament::badge color="info">
                                    {{ ucfirst($event->provider->value) }}
                                </x-filament::badge>
                            </td>
                            <td>{{ $event->recipient ?? '—' }}</td>
                            <td>{{ $event->bounce_type ?? '—' }}</td>
                            <td>
                                @if($event->url)
                                    <a href="{{ $event->url }}" target="_blank" class="fi-mail-link-underline" title="{{ $event->url }}">
                                        {{ Str::limit($event->url, 30) }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $event->occurred_at?->format('Y-m-d H:i:s') ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="fi-mail-empty">No tracking events recorded.</p>
    @endif
</div>
