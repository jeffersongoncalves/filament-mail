<div class="fi-mail-space-y-4">
    @if($subject ?? null)
        <div class="fi-mail-subject">
            <strong>Subject:</strong> {{ $subject }}
        </div>
    @endif

    @if($html ?? null)
        <iframe
            srcdoc="{{ e($html) }}"
            class="fi-mail-preview-iframe"
            style="min-height: 600px; max-width: {{ config('filament-mail.preview.max_width', '800px') }};"
            @if(config('filament-mail.preview.sandbox', true))
                sandbox="allow-same-origin"
            @endif
        ></iframe>
    @else
        <p class="fi-mail-empty">No HTML content available.</p>
    @endif
</div>
