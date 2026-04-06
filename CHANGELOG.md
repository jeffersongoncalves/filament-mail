# Changelog

All notable changes to `filament-mail` will be documented in this file.

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
