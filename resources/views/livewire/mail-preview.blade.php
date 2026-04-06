<div>
    @if($subject)
        <div class="fi-mail-subject">
            {{ $subject }}
        </div>
    @endif

    @if($html)
        <iframe
            srcdoc="{{ e($html) }}"
            class="fi-mail-preview-iframe"
            style="min-height: 400px; max-width: {{ config('filament-mail.preview.max_width', '800px') }};"
            @if(config('filament-mail.preview.sandbox', true))
                sandbox="allow-same-origin"
            @endif
        ></iframe>
    @else
        <div class="fi-mail-empty">
            No HTML content available.
        </div>
    @endif
</div>
