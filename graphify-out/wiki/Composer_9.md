# Composer (9)

> 4 nodes · cohesion 0.50

## Key Concepts

- **post-create-project-cmd** (4 connections) — `composer.json`
- **@php artisan key:generate --ansi** (1 connections) — `composer.json`
- **@php artisan migrate --graceful --ansi** (1 connections) — `composer.json`
- **@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\** (1 connections) — `composer.json`

## Relationships

- [Composer (2)](Composer_2.md) (1 shared connections)

## Source Files

- `composer.json`

## Audit Trail

- EXTRACTED: 4 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*