<?php

namespace App\Jobs;

use Domain\Product\Models\Product;
use Domain\Product\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProductJsonPropertiesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_created_json_properties(): void
    {
        $queue = Queue::getFacadeRoot();

        Queue::fake([ProductJsonProperties::class]);

        $properties = Property::factory(10)->create();

        $product = Product::factory()
            ->hasAttached($properties, function () {
                return ['value' => fake()->word()];
            })
            ->create();

        $this->assertEmpty($product->json_properties);

        Queue::swap($queue);

        ProductJsonProperties::dispatchSync($product);

        $product->refresh();

        $this->assertNotEmpty($product->json_properties);
    }
}
