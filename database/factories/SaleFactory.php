<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'invoice_number' => 'INV-'.now()->format('Ymd').'-'.fake()->unique()->randomNumber(4),
            'total' => fake()->numberBetween(10000, 100000),
            'discount' => 0,
            'tax' => 0,
            'grand_total' => fake()->numberBetween(10000, 100000),
            'payment_method' => 'cash',
            'paid_amount' => 100000,
            'change_amount' => 0,
            'cashier_id' => User::factory(),
        ];
    }
}
