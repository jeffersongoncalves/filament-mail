@php
    $variables = $getRecord()->variables ?? [];
@endphp

<div>
    @if(count($variables) > 0)
        <div class="fi-mail-table-wrap">
            <table class="fi-mail-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Example</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variables as $variable)
                        <tr>
                            <td class="fi-mail-mono">{{ '{{ $' . ($variable['name'] ?? '') . ' }}' }}</td>
                            <td>
                                <x-filament::badge color="gray">
                                    {{ $variable['type'] ?? 'string' }}
                                </x-filament::badge>
                            </td>
                            <td class="fi-mail-cell-muted">{{ $variable['example'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="fi-mail-empty">No variables defined.</p>
    @endif
</div>
