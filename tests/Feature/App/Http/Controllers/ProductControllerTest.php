<?php

namespace App\Http\Controllers;

use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_success_response()
    {
        $product = Product::factory()->create();

        $this->get(action(ProductController::class, $product))
            ->assertOk();
    }
}
