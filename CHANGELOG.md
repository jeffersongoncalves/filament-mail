# Changelog

All notable changes to `filament-mail` will be documented in this file.

## 1.3.2 - 2026-04-06

### Fixed

- Escape Blade syntax in boost guidelines to prevent compilation error

## 1.3.1 - 2026-04-06

### Documentation

- Add Template Editor section to README (driver config, Unlayer setup, custom drivers)
- Add MailNotification section to README (usage examples, HasMailTemplate trait)
- Add Laravel Boost guidelines and skills for AI-assisted development

## 1.3.0 - 2026-04-06

### Added

- Swappable template editor via `TemplateEditorContract` (config: `filament-mail.template_editor.driver`)
- `RichEditorDriver` (default) — standard Filament RichEditor
- `UnlayerEditorDriver` — visual drag-and-drop email editor via Unlayer CDN
- `UnlayerField` custom Filament component with Alpine.js integration
- `MailNotification` class for sending template-based notifications with variable binding
- `HasMailTemplate` trait for Mailable integration
- `body_design` migration for storing Unlayer JSON designs
- Email template view (`emails.template`) for notification rendering
- Config options: `driver`, `unlayer_project_id`, `merge_tags`
- Comprehensive tests for editors and notifications (37 tests)

## 1.2.0 - 2026-04-06

### Changed

- Replace Tailwind CSS classes with custom CSS using `fi-mail-*` prefix
- Add PostCSS + cssnano build pipeline for CSS compilation
- Register compiled CSS asset via FilamentAsset in ServiceProvider
- Uses Filament CSS custom properties for theme integration and dark mode

## 1.1.1 - 2026-04-06

### Fixed

- Fix MailDashboard extending Dashboard causing route conflict with main dashboard (RouteNotFoundException for filament.admin.pages.dashboard)

## 1.1.0 - 2026-04-06

### What's Changed

#### Added

- Integrated `filament/spatie-laravel-translatable-plugin` for native locale switching on mail templates
- LocaleSwitcher action on all template pages (list, create, edit, view)
- SpatieLaravelTranslatablePlugin setup documentation in README

#### Changed

- Simplified template form: direct `subject`, `html_body`, `text_body` fields (plugin handles locale switching)
- Removed manual locale tabs and `processTranslations()` logic

#### Requirements

- `filament/spatie-laravel-translatable-plugin` ^3.0 (Filament v3)

## [Unreleased]
