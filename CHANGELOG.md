# Changelog

All notable changes to `filament-mail` will be documented in this file.

## 2.3.5 - 2026-04-06

### Changed

- Remove Mailable Class field from template form and infolist
- Remove Layout field from template form and infolist

## 2.3.4 - 2026-04-06

### Fixed

- Fix Unlayer editor iframe not filling container height (display grid + 700px)
- Fix DataCloneError on design export (wrap in try/catch with deep clone)
- Remove Plain Text Body field from template form

## 2.3.3 - 2026-04-06

### Fixed

- Fix Unlayer editor not loading: remove defer from embed.js, fix state path for body_design, build options as PHP array to avoid Blade-in-Alpine conflicts

## 2.3.2 - 2026-04-06

### Fixed

- Escape Blade syntax in boost guidelines to prevent compilation error

## 2.3.1 - 2026-04-06

### Documentation

- Add Template Editor section to README (driver config, Unlayer setup, custom drivers)
- Add MailNotification section to README (usage examples, HasMailTemplate trait)
- Add Laravel Boost guidelines and skills for AI-assisted development

## 2.3.0 - 2026-04-06

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

## 2.2.0 - 2026-04-06

### Changed

- Replace Tailwind CSS classes with custom CSS using `fi-mail-*` prefix
- Add PostCSS + cssnano build pipeline for CSS compilation
- Register compiled CSS asset via FilamentAsset in ServiceProvider
- Uses Filament CSS custom properties for theme integration and dark mode

## 2.1.1 - 2026-04-06

### Fixed

- Fix MailDashboard extending Dashboard causing route conflict with main dashboard (RouteNotFoundException for filament.admin.pages.dashboard)

## 2.1.0 - 2026-04-06

### What's Changed

#### Added

- Integrated `lara-zeus/spatie-translatable` for native locale switching on mail templates
- LocaleSwitcher action on all template pages (list, create, edit, view)
- SpatieTranslatablePlugin setup documentation in README

#### Changed

- Simplified template form: direct `subject`, `html_body`, `text_body` fields (plugin handles locale switching)
- Removed manual locale tabs and `processTranslations()` logic

#### Requirements

- `lara-zeus/spatie-translatable` ^1.0 (Filament v4)

## 2.0.0 - 2026-04-06

### Initial Release (Filament v4)

Complete email management UI for Filament v4.

#### Features

- **MailLogResource** — Browse, search, view emails with HTML preview, attachments, headers, metadata, tracking events. Resend, retry, and preview actions.
- **MailTemplateResource** — CRUD with multi-locale tabs, variables repeater, preview, send test email, duplicate, version history.
- **MailSuppressionResource** — Manage suppressed emails (hard bounces, complaints, manual). Unsuppress in bulk.
- **Mail Dashboard** — Stats overview (4 cards), daily analytics line chart, delivery rate doughnut chart, recent emails and bounces tables with period filter.
- **Widgets** — MailStatsOverview, MailAnalyticsChart, MailDeliveryRateChart.
- **Plugin** — Fluent API to toggle resources, widgets, dashboard, navigation group, tenant scoping.

#### Requirements

- PHP 8.2+
- Laravel 11+
- Filament 4.x
- jeffersongoncalves/laravel-mail ^1.2

## [Unreleased]
