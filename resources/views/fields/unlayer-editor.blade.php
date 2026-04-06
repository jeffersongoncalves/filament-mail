@once
    <script src="https://editor.unlayer.com/embed.js" defer></script>
@endonce

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            state: $wire.$entangle('{{ $getStatePath() }}'),
            designState: $wire.$entangle('{{ $getStatePath('body_design') }}'),
            editor: null,

            init() {
                this.$nextTick(() => {
                    this.initEditor();
                });
            },

            initEditor() {
                const container = this.$refs.editorContainer;
                if (!container || typeof unlayer === 'undefined') {
                    setTimeout(() => this.initEditor(), 200);
                    return;
                }

                const isDark = document.documentElement.classList.contains('dark');

                unlayer.init({
                    id: container.id,
                    projectId: {{ $getProjectId() ? $getProjectId() : 'undefined' }},
                    displayMode: 'email',
                    appearance: {
                        theme: isDark ? 'modern_dark' : 'modern_light',
                    },
                    mergeTags: @js($getMergeTags()),
                });

                this.editor = unlayer;

                // Load existing design
                const design = this.designState;
                if (design) {
                    try {
                        const parsed = typeof design === 'string' ? JSON.parse(design) : design;
                        unlayer.loadDesign(parsed);
                    } catch (e) {
                        console.warn('Failed to load Unlayer design:', e);
                    }
                }

                // Auto-export on design update
                unlayer.addEventListener('design:updated', () => {
                    this.exportContent();
                });
            },

            exportContent() {
                if (!this.editor) return;

                this.editor.exportHtml((data) => {
                    this.state = data.html;
                });

                this.editor.saveDesign((design) => {
                    this.designState = JSON.stringify(design);
                });
            },
        }"
        x-on:submit.prevent="exportContent()"
        wire:ignore
    >
        <div
            x-ref="editorContainer"
            id="unlayer-editor-{{ $getId() }}"
            style="min-height: 600px;"
        ></div>
    </div>
</x-dynamic-component>
