# CHANGELOG

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v1.3.1] - 2025-07-23

- Fix `TelegramNotifierServiceProvider` configuration for Laravel Facade alias.

## [v1.3.0] - 2025-07-06

- Fix `TelegramNotifierServiceProvider` configuration for Laravel install.
- Verified compatibility with Laravel 12

## [v1.2.0] - 2025-05-19

### Added
- Support for Laravel Facade `TelegramNotifier` to simplify integration in Laravel projects.

## [v1.1.0] - 2025-05-13

### Added
- Support for returning full API response from `send()` method.

### Changed
- `send()` method now returns an array instead of a boolean.

---

## [v1.0.1] - 2025-04-12

### Added
- Initial release.
- TelegramNotifier class with Guzzle integration.
- Laravel ServiceProvider for easy integration.
- Composer compatibility with Laravel (via `extra.laravel.providers`).
- Basic PHPUnit test suite.