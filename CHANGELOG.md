# Changelog

All notable changes to `filament-mail` will be documented in this file.

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
