<?php

namespace Database\Seeders;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Product\Models\Option;
use Domain\Product\Models\OptionValue;
use Domain\Product\Models\Product;
use Domain\Product\Models\Property;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Brand::factory(20)->create();

        Option::factory(2)->create();

        $optionValues = OptionValue::factory(10)->create();

        $properties = Property::factory(10)->create();

        Category::factory(10)
            ->has(
                Product::factory(10)
                    ->hasAttached($optionValues)
                    ->hasAttached($properties, function () {
                        return ['value' => ucfirst(fake()->word())];
                    })
            )
            ->create();

    }
}
