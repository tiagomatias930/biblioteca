<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'is_protected',
        'access_code',
        'sort_order',
    ];

    protected $hidden = [
        'access_code',
    ];

    protected function casts(): array
    {
        return [
            'is_protected' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Auto-generate a unique slug from the name whenever it's set,
     * unless a slug was explicitly provided.
     */
    public static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = static::uniqueSlugFrom($category->name);
            }
        });
    }

    public static function uniqueSlugFrom(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-" . ++$i;
        }

        return $slug;
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class)->orderBy('title');
    }

    public function setAccessCodeAttribute(?string $value): void
    {
        $this->attributes['access_code'] = $value ? Hash::make($value) : null;
    }

    public function checkAccessCode(string $code): bool
    {
        if (! $this->access_code) {
            return false;
        }

        return Hash::check($code, $this->access_code);
    }

    public function coverImageUrl(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-category-cover.svg');
    }
}
