<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class CompanyFactory extends Factory
{
   
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company,
            'street_address' => $this->faker->streetAddress,
            'representative_name' => $this->faker->name,
        ];
    }
}