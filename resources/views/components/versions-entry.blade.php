@php
    $versions = $getRecord()->versions()->orderBy('version_number', 'desc')->get();
@endphp

<div>
    @if($versions->isNotEmpty())
        <div class="fi-mail-table-wrap">
            <table class="fi-mail-table">
                <thead>
                    <tr>
                        <th>Version</th>
                        <th>Change Note</th>
                        <th>Author</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($versions as $version)
                        <tr>
                            <td>
                                <x-filament::badge color="gray">
                                    v{{ $version->version_number }}
                                </x-filament::badge>
                            </td>
                            <td>{{ $version->change_note ?? '—' }}</td>
                            <td>{{ $version->author ?? '—' }}</td>
                            <td>{{ $version->created_at?->format('Y-m-d H:i:s') ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="fi-mail-empty">No version history.</p>
    @endif
</div>
