<?php

namespace Domain\Catalog\Observers;

use Domain\Catalog\Models\Brand;
use Illuminate\Support\Facades\Cache;

class BrandObserver
{
    public function created(Brand $brand): void
    {
        $this->clearCacheForHomePage();
    }

    public function updated(Brand $brand): void
    {
        $this->clearCacheForHomePage();
    }

    public function deleted(Brand $brand): void
    {
        $this->clearCacheForHomePage();
    }

    public function restored(Brand $brand): void
    {
        $this->clearCacheForHomePage();
    }

    public function forceDeleted(Brand $brand): void
    {
        $this->clearCacheForHomePage();
    }

    private function clearCacheForHomePage(): void
    {
        Cache::forget('brand_home_page');
        Cache::forget('brand_catalog_page');
    }
}
