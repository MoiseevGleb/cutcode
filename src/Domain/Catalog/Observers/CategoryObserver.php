<?php

namespace Domain\Catalog\Observers;

use Domain\Catalog\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    public function created(Category $category): void
    {
        $this->clearCacheForHomePage();
    }

    public function updated(Category $category): void
    {
        $this->clearCacheForHomePage();
    }

    public function deleted(Category $category): void
    {
        $this->clearCacheForHomePage();
    }

    public function restored(Category $category): void
    {
        $this->clearCacheForHomePage();
    }

    public function forceDeleted(Category $category): void
    {
        $this->clearCacheForHomePage();
    }

    private function clearCacheForHomePage(): void
    {
        Cache::forget('categories_home_page');
        Cache::forget('categories_catalog_page');
    }
}
