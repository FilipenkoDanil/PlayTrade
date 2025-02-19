<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::with('game')->get();

        foreach ($categories as $category) {
            $attributes = Attribute::where('game_id', $category->game_id)->get();

            foreach ($attributes as $attribute) {
                DB::table('attribute_category')->insert([
                    'category_id' => $category->id,
                    'attribute_id' => $attribute->id,
                ]);
            }
        }
    }
}
