<?php

namespace App\Filters;

use Domain\Catalog\Filters\AbstractFilter;
use Domain\Catalog\Models\Brand;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

class BrandFilter extends AbstractFilter
{
    public function title(): string
    {
        return 'Бренд';
    }

    public function key(): string
    {
        return 'brands';
    }

    public function apply(Builder $query): Builder
    {
        return $query->when($this->requestValue(), function (Builder $query) {
            $query->whereIn('brand_id', $this->requestValue());
        });
    }

    public function values(): array
    {
        return Cache::rememberForever('brands_catalog_page', function () {
            return Brand::query()
                ->select('id', 'title')
                ->has('products')
                ->get();
        })->pluck('title', 'id')->toArray();
    }

    public function view(): string
    {
        return 'catalog.filters.brands';
    }
}
