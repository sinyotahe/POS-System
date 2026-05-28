<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'sku' => fake()->unique()->ean13(),
            'barcode' => fake()->unique()->ean13(),
            'name' => fake()->unique()->words(3, true),
            'cost_price' => fake()->numberBetween(1000, 50000),
            'sell_price' => fake()->numberBetween(5000, 100000),
            'minimum_stock' => fake()->numberBetween(1, 10),
            'status' => true,
        ];
    }
}
