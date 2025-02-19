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
        Schema::table('deals', function (Blueprint $table) {
            $table->string('offer_game')->after('offer_server');
            $table->string('offer_category')->after('offer_game');
            $table->string('offer_unit')->after('offer_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('offer_game');
            $table->dropColumn('offer_category');
            $table->dropColumn('offer_unit');
        });
    }
};
