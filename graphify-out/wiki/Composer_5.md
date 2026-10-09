# Composer (5)

> 8 nodes · cohesion 0.25

## Key Concepts

- **setup** (7 connections) — `composer.json`
- **post-root-package-install** (2 connections) — `composer.json`
- **@php -r \"file_exists('.env') || copy('.env.example', '.env');\** (2 connections) — `composer.json`
- **composer install** (1 connections) — `composer.json`
- **npm install** (1 connections) — `composer.json`
- **npm run build** (1 connections) — `composer.json`
- **@php artisan key:generate** (1 connections) — `composer.json`
- **@php artisan migrate --force** (1 connections) — `composer.json`

## Relationships

- [Composer (2)](Composer_2.md) (2 shared connections)

## Source Files

- `composer.json`

## Audit Trail

- EXTRACTED: 9 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*