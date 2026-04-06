@php
    $attachments = $getRecord()->attachments ?? [];
@endphp

<div>
    @if(count($attachments) > 0)
        <div class="fi-mail-table-wrap">
            <table class="fi-mail-table">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Content Type</th>
                        <th>Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attachments as $attachment)
                        <tr>
                            <td class="fi-mail-cell-bold">{{ $attachment['filename'] ?? 'Unknown' }}</td>
                            <td>{{ $attachment['content_type'] ?? '—' }}</td>
                            <td>
                                @if(isset($attachment['size']))
                                    {{ number_format($attachment['size'] / 1024, 1) }} KB
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if(isset($attachment['path']) && isset($attachment['disk']))
                                    <a href="{{ Storage::disk($attachment['disk'])->url($attachment['path']) }}"
                                       target="_blank"
                                       class="fi-mail-link">
                                        Download
                                    </a>
                                @else
                                    <span class="fi-mail-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="fi-mail-empty">No attachments.</p>
    @endif
</div>
