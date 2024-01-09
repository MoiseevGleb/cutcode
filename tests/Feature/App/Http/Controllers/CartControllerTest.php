<?php

namespace App\Http\Controllers;

use Domain\Cart\CartManager;
use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CartManager::fake();
    }

    /**
     * @test
     */
    public function it_is_empty_cart()
    {
        $this->get(action([CartController::class, 'index']))
            ->assertOk()
            ->assertViewIs('cart.index')
            ->assertViewHas('items', collect([]));
    }

    /**
     * @test
     */
    public function it_is_not_empty_cart()
    {
        $product = Product::factory()->create();

        cart()->add($product);

        $this->get(action([CartController::class, 'index']))
            ->assertOk()
            ->assertViewIs('cart.index')
            ->assertViewHas('items', cart()->items());
    }

    /**
     * @test
     */
    public function it_added_success()
    {
        $product = Product::factory()->create();

        $this->assertEquals(0, cart()->count());

        $this->post(action([CartController::class, 'add'], $product), ['quantity' => 4]);

        $this->assertEquals(4, cart()->count());
    }

    /**
     * @test
     */
    public function it_quantity_changed()
    {
        $product = Product::factory()->create();

        cart()->add($product, 4);

        $this->assertEquals(4, cart()->count());

        $this->post(action([CartController::class, 'quantity'], cart()->items()->first()), ['quantity' => 8]);

        $this->assertEquals(8, cart()->count());
    }


    /**
     * @test
     */
    public function it_delete_success()
    {
        $product = Product::factory()->create();

        cart()->add($product, 4);

        $this->assertEquals(4, cart()->count());

        $this->delete(action([CartController::class, 'remove'], cart()->items()->first()));

        $this->assertEquals(0, cart()->count());
    }

    /**
     * @test
     */
    public function it_clear_success()
    {
        $products = Product::factory(2)->create();

        cart()->add($products->get(0), 4);
        cart()->add($products->get(1), 8);

        $this->assertEquals(12, cart()->count());

        $this->delete(action([CartController::class, 'clear']));

        $this->assertEquals(0, cart()->count());
    }
}
