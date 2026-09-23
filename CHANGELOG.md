# Changelog

All notable changes to `filament-mail` will be documented in this file.

## 3.4.0 - 2026-09-23

### What's new

- **Translations:** 17 new locales (ar, az, de, es, fa, fr, hi, it, ja, nl, pl, pt, ru, tr, uk, uz, zh_CN). (#11)

Thanks to @Elvin-Qulizade (Elvin Qulizada) for the i18n initiative behind these translations — first contributed in jeffersongoncalves/filament-scanner-guard#2 and now rolled out across the Filament plugins. He is credited as co-author.

### What's Changed

* ci: standardize dependabot config by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-mail/pull/1
* chore(deps): bump the actions-deps group with 2 updates by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-mail/pull/6
* chore(deps-dev): bump the npm-deps group with 2 updates by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-mail/pull/7
* feat(i18n): add translations (3.x) by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-mail/pull/11

**Full Changelog**: https://github.com/jeffersongoncalves/filament-mail/compare/3.3.5...3.4.0

## 3.3.5 - 2026-04-06

### Changed

- Remove Mailable Class field from template form and infolist
- Remove Layout field from template form and infolist

## 3.3.4 - 2026-04-06

### Fixed

- Fix Unlayer editor iframe not filling container height (display grid + 700px)
- Fix DataCloneError on design export (wrap in try/catch with deep clone)
- Remove Plain Text Body field from template form

## 3.3.3 - 2026-04-06

### Fixed

- Fix Unlayer editor not loading: remove defer from embed.js, fix state path for body_design, build options as PHP array to avoid Blade-in-Alpine conflicts

## 3.3.2 - 2026-04-06

### Fixed

- Escape Blade syntax in boost guidelines to prevent compilation error

## 3.3.1 - 2026-04-06

### Documentation

- Add Template Editor section to README (driver config, Unlayer setup, custom drivers)
- Add MailNotification section to README (usage examples, HasMailTemplate trait)
- Add Laravel Boost guidelines and skills for AI-assisted development

## 3.3.0 - 2026-04-06

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

## 3.2.0 - 2026-04-06

### Changed

- Replace Tailwind CSS classes with custom CSS using `fi-mail-*` prefix
- Add PostCSS + cssnano build pipeline for CSS compilation
- Register compiled CSS asset via FilamentAsset in ServiceProvider
- Uses Filament CSS custom properties for theme integration and dark mode

## 3.1.2 - 2026-04-06

### Fixed

- Use correct Filament v5 API types (Schema, BackedEnum, non-static view) for MailDashboard

## 3.1.1 - 2026-04-06

### Fixed

- Fix MailDashboard extending Dashboard causing route conflict with main dashboard (RouteNotFoundException for filament.admin.pages.dashboard)

## 3.1.0 - 2026-04-06

### What's Changed

#### Added

- Integrated `lara-zeus/spatie-translatable` for native locale switching on mail templates
- LocaleSwitcher action on all template pages (list, create, edit, view)
- SpatieTranslatablePlugin setup documentation in README

#### Changed

- Simplified template form: direct `subject`, `html_body`, `text_body` fields (plugin handles locale switching)
- Removed manual locale tabs and `processTranslations()` logic

#### Requirements

- `lara-zeus/spatie-translatable` ^2.0 (Filament v5)

## 1.0.0 - 2026-04-06

### Initial Release (Filament v3)

Complete email management UI for Filament v3.

#### Features

- **MailLogResource** — Browse, search, view emails with HTML preview, attachments, headers, metadata, tracking events. Resend, retry, and preview actions.
- **MailTemplateResource** — CRUD with multi-locale tabs, variables repeater, preview, send test email, duplicate, version history.
- **MailSuppressionResource** — Manage suppressed emails (hard bounces, complaints, manual). Unsuppress in bulk.
- **Mail Dashboard** — Stats overview (4 cards), daily analytics line chart, delivery rate doughnut chart, recent emails and bounces tables with period filter.
- **Widgets** — MailStatsOverview, MailAnalyticsChart, MailDeliveryRateChart.
- **Plugin** — Fluent API to toggle resources, widgets, dashboard, navigation group, tenant scoping.

#### Requirements

- PHP 8.1+
- Laravel 10+
- Filament 3.x
- jeffersongoncalves/laravel-mail ^1.2

## 3.0.0 - 2026-04-06

### Initial Release (Filament v5)

Complete email management UI for Filament v5.

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
- Filament 5.x
- jeffersongoncalves/laravel-mail ^1.2

## [Unreleased]
