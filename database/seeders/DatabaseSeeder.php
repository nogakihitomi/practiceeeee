<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ProductsTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(SalesTableSeeder::class);
    }
}