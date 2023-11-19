<?php

namespace Support\Casts;

use Domain\Product\Models\Product;
use Support\ValueObjects\Price;
use Tests\TestCase;

class PriceCastTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_success()
    {
        $p = Product::factory()->create([
            'price' => 10000,
        ]);

        $product = Product::query()->find($p->id);

        $this->assertInstanceOf(Price::class, $product->price);
    }

    /**
     * @test
     */
    public function it_set_success()
    {
        $price = Price::make(10000);

        $product = Product::factory()->create([
            'price' => $price,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);

        $this->assertEquals(
            $price->raw(),
            Product::query()->find($product->id)->price->raw()
        );
    }
}
