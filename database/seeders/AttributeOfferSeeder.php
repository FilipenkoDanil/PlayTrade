<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = Offer::with('category.attributes')->get();

        foreach ($offers as $offer) {
            foreach ($offer->category->attributes as $attribute) {
                DB::table('attribute_offer')->insert([
                    'offer_id' => $offer->id,
                    'attribute_id' => $attribute->id,
                    'value' => fake()->randomElement(['10%', 'High', 'Medium', 'Low', 'Yes', 'No']),
                ]);
            }
        }
    }
}
