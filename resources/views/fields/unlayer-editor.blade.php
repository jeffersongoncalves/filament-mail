@once
    <script src="https://editor.unlayer.com/embed.js"></script>
@endonce

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php
        $statePath = $getStatePath();
        $projectId = $getProjectId();
        $mergeTags = $getMergeTags();

        // body_design lives as a sibling field — derive its path
        $pathParts = explode('.', $statePath);
        array_pop($pathParts);
        $designPath = implode('.', array_merge($pathParts, ['body_design']));

        $unlayerOptions = [
            'displayMode' => 'email',
            'mergeTags' => $mergeTags,
        ];

        if ($projectId) {
            $unlayerOptions['projectId'] = $projectId;
        }
    @endphp

    <div
        x-data="{
            state: $wire.$entangle('{{ $statePath }}'),
            designState: $wire.$entangle('{{ $designPath }}'),
            editor: null,
            ready: false,

            init() {
                this.$nextTick(() => this.waitForUnlayer());
            },

            waitForUnlayer() {
                if (typeof unlayer === 'undefined' || typeof unlayer.init !== 'function') {
                    setTimeout(() => this.waitForUnlayer(), 300);
                    return;
                }
                this.initEditor();
            },

            initEditor() {
                const container = this.$refs.editorContainer;
                if (!container) return;

                const isDark = document.documentElement.classList.contains('dark');
                const options = @js($unlayerOptions);

                options.id = container.id;
                options.appearance = {
                    theme: isDark ? 'modern_dark' : 'modern_light',
                };

                unlayer.init(options);
                this.editor = unlayer;
                this.ready = true;

                // Load existing design
                const design = this.designState;
                if (design) {
                    try {
                        const parsed = typeof design === 'string' ? JSON.parse(design) : design;
                        unlayer.loadDesign(parsed);
                    } catch (e) {
                        console.warn('Filament Mail: Failed to load Unlayer design', e);
                    }
                }

                // Auto-export on design update
                unlayer.addEventListener('design:updated', () => {
                    this.exportContent();
                });
            },

            exportContent() {
                if (!this.ready) return;

                try {
                    this.editor.exportHtml((data) => {
                        this.state = data.html;
                    });
                } catch (e) {}

                try {
                    this.editor.saveDesign((design) => {
                        this.designState = JSON.stringify(JSON.parse(JSON.stringify(design)));
                    });
                } catch (e) {}
            },
        }"
        x-on:submit.prevent="exportContent()"
        wire:ignore
    >
        <div
            x-ref="editorContainer"
            id="unlayer-editor-{{ $getId() }}"
            style="height: 700px;"
        ></div>
    </div>
</x-dynamic-component>
