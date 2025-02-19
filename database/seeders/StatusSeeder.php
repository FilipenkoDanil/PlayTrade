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
            // Статусы для сделок (deals)
            ['title' => 'In Progress', 'type' => 'deal'], // Выполняется
            ['title' => 'Completed', 'type' => 'deal'],   // Закрыто (выполнено)
            ['title' => 'Cancelled', 'type' => 'deal'],   // Отменено
            ['title' => 'Disputed', 'type' => 'deal'],    // Спор

            // Статусы для транзакций (transactions)
            ['title' => 'Pending', 'type' => 'transaction'],  // Ожидание
            ['title' => 'Completed', 'type' => 'transaction'], // Выполнено
            ['title' => 'Failed', 'type' => 'transaction'],   // Ошибка
            ['title' => 'Cancelled', 'type' => 'transaction'], // Отменено
            ['title' => 'Refunded', 'type' => 'transaction'], // Возврат
        ];

        foreach ($statuses as $status) {
            Status::firstOrCreate(['title' => $status['title'], 'type' => $status['type']]);
        }
    }
}
