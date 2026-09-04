<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class InventoryCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $category): void {
            if (blank($category->slug)) {
                $category->slug = Str::slug((string) $category->name);
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Three-letter abbreviation used in asset tags: "IT Equipment" → "ITE".
     */
    public function tagAbbreviation(): string
    {
        $letters = preg_replace('/[^A-Za-z]/', '', (string) $this->slug) ?: 'GEN';

        return strtoupper(substr($letters, 0, 3));
    }
}
