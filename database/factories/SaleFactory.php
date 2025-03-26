<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class SaleFactory extends Factory
{

    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}