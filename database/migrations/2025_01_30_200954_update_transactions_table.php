<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('balance_before');
            $table->dropColumn('balance_after');

            $table->double('amount', 8, 2)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->double('balance_before', 8, 2);
            $table->double('balance_after', 8, 2);

            $table->dropColumn('amount');
        });
    }
};
