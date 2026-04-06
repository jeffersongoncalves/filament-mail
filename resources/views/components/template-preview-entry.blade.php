@php
    $record = $getRecord();
    $locale = $locale ?? config('filament-mail.template_editor.default_locale', 'en');

    try {
        $preview = app(\JeffersonGoncalves\LaravelMail\Actions\PreviewTemplateAction::class)
            ->execute($record, [], $locale);
        $html = $preview['html'];
        $subject = $preview['subject'];
    } catch (\Throwable $e) {
        $html = null;
        $subject = null;
    }
@endphp

<div class="fi-mail-space-y-3">
    @if($subject)
        <div class="fi-mail-subject">
            <strong>Subject:</strong> {{ $subject }}
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
        <p class="fi-mail-empty">
            No content available for this locale. Make sure the template has content and the locale is configured.
        </p>
    @endif
</div>
