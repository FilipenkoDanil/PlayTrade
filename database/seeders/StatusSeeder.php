<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['title' => 'In Progress', 'type' => 'deal'],
            ['title' => 'Completed', 'type' => 'deal'],
            ['title' => 'Cancelled', 'type' => 'deal'],
            ['title' => 'Disputed', 'type' => 'deal'],

            ['title' => 'Pending', 'type' => 'transaction'],
            ['title' => 'Completed', 'type' => 'transaction'],
            ['title' => 'Failed', 'type' => 'transaction'],
            ['title' => 'Cancelled', 'type' => 'transaction'],
            ['title' => 'Refunded', 'type' => 'transaction'],
        ];

        foreach ($statuses as $status) {
            Status::firstOrCreate(['title' => $status['title'], 'type' => $status['type']]);
        }
    }
}
