<?php

namespace App\Http\Controllers;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_page_success()
    {
        Product::factory(5)->onHomePage()->create([
            'sorting' => 999,
        ]);

        $product = Product::factory()->onHomePage()->createOne([
            'sorting' => 1,
        ]);

        Category::factory(5)->onHomePage()->create([
            'sorting' => 999,
        ]);

        $category = Category::factory()->onHomePage()->createOne([
            'sorting' => 1,
        ]);

        Brand::factory(5)->onHomePage()->create([
            'sorting' => 999,
        ]);

        $brand = Brand::factory()->onHomePage()->createOne([
            'sorting' => 1,
        ]);

        $this->get(action(HomeController::class, '__invoke'))
            ->assertOk()
            ->assertViewHas('categories.0', $category)
            ->assertViewHas('products.0', $product)
            ->assertViewHas('brands.0', $brand);
    }
}
