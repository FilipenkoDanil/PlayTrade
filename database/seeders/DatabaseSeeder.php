<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            GameSeeder::class,
            UnitSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            ServerSeeder::class,
            StatusSeeder::class,
            CategoryServerSeeder::class,
            OfferSeeder::class,
            AttributeCategorySeeder::class,
            AttributeOfferSeeder::class,
            RolesSeeder::class,
        ]);
    }
}
