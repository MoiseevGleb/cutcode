<?php

namespace Database\Factories;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{

    protected $model = Brand::class;

    public function definition(): array
    {
        return [
            'on_home_page' => $this->faker->boolean(),

            'sorting' => $this->faker->numberBetween(1, 999),

            'title' => $this->faker->company(),

            'thumbnail' => $this->faker->fixturesImage('brands', 'brands'),
        ];
    }

    public function onHomePage(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'on_home_page' => true,
            ];
        });
    }
}
