# Composer (2)

> 11 nodes · cohesion 0.18

## Key Concepts

- **scripts** (9 connections) — `composer.json`
- **dev** (3 connections) — `composer.json`
- **test** (3 connections) — `composer.json`
- **post-update-cmd** (2 connections) — `composer.json`
- **pre-package-uninstall** (2 connections) — `composer.json`
- **Composer\\Config::disableProcessTimeout** (1 connections) — `composer.json`
- **Illuminate\\Foundation\\ComposerScripts::prePackageUninstall** (1 connections) — `composer.json`
- **npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1 --timeout=0\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite --kill-others** (1 connections) — `composer.json`
- **@php artisan config:clear --ansi** (1 connections) — `composer.json`
- **@php artisan test** (1 connections) — `composer.json`
- **@php artisan vendor:publish --tag=laravel-assets --ansi --force** (1 connections) — `composer.json`

## Relationships

- [Composer (5)](Composer_5.md) (2 shared connections)
- [Composer](Composer.md) (1 shared connections)
- [Composer (8)](Composer_8.md) (1 shared connections)
- [Composer (9)](Composer_9.md) (1 shared connections)

## Source Files

- `composer.json`

## Audit Trail

- EXTRACTED: 15 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*