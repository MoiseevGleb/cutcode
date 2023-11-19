<?php

namespace App\Filters;

use Domain\Catalog\Filters\AbstractFilter;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

class PriceFilter extends AbstractFilter
{
    public function title(): string
    {
        return 'Цена';
    }

    public function key(): string
    {
        return 'price';
    }

    public function apply(Builder $query): Builder
    {
        return $query->when($this->requestValue(), function (\Illuminate\Database\Eloquent\Builder $q) {
            $q->whereBetween('price', [
                $this->requestValue('from', request('min_price')) * 100,
                $this->requestValue('to', request('max_price')) * 100,
            ]);
        });
    }

    public function values(): array
    {
        return [
            'from' => Cache::remember('min_price', 350, function () {
                return Product::query()->min('price') / 100;
            }),
            'to' => Cache::remember('max_price', 350, function () {
                return Product::query()->max('price') / 100;
            }),
        ];
    }

    public function view(): string
    {
        return 'catalog.filters.price';
    }
}
