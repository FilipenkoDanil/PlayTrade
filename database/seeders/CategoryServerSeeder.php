<?php

namespace Database\Seeders;

use Database\Factories\CategoryServerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryServerFactory::new()->count(15)->create();
    }
}
