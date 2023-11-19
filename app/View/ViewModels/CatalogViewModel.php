<?php

namespace App\View\ViewModels;

use Domain\Catalog\Collections\CategoryCollection;
use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Spatie\ViewModels\ViewModel;

class CatalogViewModel extends ViewModel
{
    public function __construct(
        public Category $category,
    ){}

    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->select('id', 'title', 'slug', 'price', 'thumbnail', 'json_properties')
            ->search()
            ->withCategory($this->category)
            ->filtered()
            ->sorted()
            ->paginate(6);
    }

    public function categories(): CategoryCollection
    {
        return Cache::rememberForever('categories_catalog_page', function () {
            return Category::query()
                ->select('id', 'title', 'slug')
                ->has('products')
                ->get();
        });
    }
}
