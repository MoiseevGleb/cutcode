<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\View\ViewModels\CatalogViewModel;
use Domain\Catalog\Models\Category;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): CatalogViewModel
    {
        return (new CatalogViewModel($category))->view('catalog.index');
    }
}
