<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'images',
        'category',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'images' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope for active products only.
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** @return HasMany<TransactionItem, $this> */
    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Return the primary image URL or a valid fallback image URL.
     * Incorporates timestamp-based cache busting for stale browser prevention.
     */
    public function getFirstImageUrl(): string
    {
        $images = $this->images ?? [];

        if (! empty($images) && ! empty($images[0])) {
            $imagePath = $images[0];
            $url = Str::startsWith($imagePath, ['http://', 'https://'])
                ? $imagePath
                : asset('storage/'.ltrim($imagePath, '/'));

            if ($this->updated_at) {
                $url .= '?v='.$this->updated_at->timestamp;
            }

            return $url;
        }

        return $this->getFallbackImageUrl();
    }

    /**
     * Fallback image URL when a product has no image in database/CMS.
     */
    public function getFallbackImageUrl(): string
    {
        $catalogName = strtolower($this->name.' '.$this->slug);
        foreach (['c25', 'c26', 'c27', 'c28', 'hexa', 'pillow'] as $shape) {
            if (preg_match('/(?<![a-z0-9])'.$shape.'(?![a-z0-9])/i', $catalogName)) {
                $staticRel = 'images/sumatra/'.$shape.'.png';
                if (file_exists(public_path($staticRel))) {
                    return asset($staticRel);
                }
            }
        }

        if (file_exists(public_path('images/placeholder.svg'))) {
            return asset('images/placeholder.svg');
        }

        return asset('images/placeholder.png');
    }
}
