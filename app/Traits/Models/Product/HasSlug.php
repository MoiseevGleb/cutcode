<?php

namespace App\Traits\Models\Product;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->slug = self::getSlug($model);
        });
    }

    public static function getSlug(Model $model): string
    {
        if ($model->slug) {
            return $model->slug;
        }

        $defaultSlug = str($model->{self::slugFrom()})->slug();

        $equalSlugCounter = 0;
        $slug = $defaultSlug;

        while ($model::query()->where('slug', 'like', $slug)->exists()) {
            $equalSlugCounter++;
            $slug = "{$defaultSlug}-{$equalSlugCounter}";
        }

        return $slug;
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
