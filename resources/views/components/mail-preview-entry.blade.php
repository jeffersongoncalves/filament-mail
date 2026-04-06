@php
    $html = $getRecord()->html_body;
@endphp

<div>
    @if($html)
        <iframe
            srcdoc="{{ e($html) }}"
            class="fi-mail-preview-iframe"
            style="min-height: 500px; max-width: {{ config('filament-mail.preview.max_width', '800px') }};"
            @if(config('filament-mail.preview.sandbox', true))
                sandbox="allow-same-origin"
            @endif
        ></iframe>
    @else
        <p class="fi-mail-empty">No HTML content available.</p>
    @endif
</div>
