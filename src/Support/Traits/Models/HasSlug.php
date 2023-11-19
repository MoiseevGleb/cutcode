<?php

namespace Support\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->makeSlug();
        });
    }

    protected function makeSlug(): void
    {
        if ($this->slug) {
            return;
        }

        $defaultSlug = str($this->{self::slugFrom()})->slug();
        $slug = $defaultSlug;

        $equalSlugCounter = 0;

        while ($this->slugExists($slug))
        {
            $equalSlugCounter++;
            $slug = "$defaultSlug-$equalSlugCounter";
        }

        $this->slug = $slug;
    }

    private function slugExists(string $slug): bool
    {
        return $this->query()
            ->where('slug', 'like', $slug)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->withoutGlobalScopes()
            ->exists();
    }

    protected static function slugFrom(): string
    {
        return 'title';
    }
}
