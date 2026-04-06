<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentMail\Forms\Components;

use Filament\Forms\Components\Field;

class UnlayerField extends Field
{
    protected string $view = 'filament-mail::fields.unlayer-editor';

    protected ?string $projectId = null;

    /** @var array<int, array<string, string>> */
    protected array $mergeTags = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectId(config('filament-mail.template_editor.unlayer_project_id'));

        $mergeTags = config('filament-mail.template_editor.merge_tags', []);
        if (is_array($mergeTags)) {
            $this->mergeTags = $mergeTags;
        }
    }

    public function projectId(?string $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getProjectId(): ?string
    {
        return $this->projectId;
    }

    /**
     * @param  array<int, array<string, string>>  $mergeTags
     */
    public function mergeTags(array $mergeTags): static
    {
        $this->mergeTags = $mergeTags;

        return $this;
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getMergeTags(): array
    {
        return $this->mergeTags;
    }
}
