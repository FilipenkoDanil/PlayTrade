<?php

use App\Models\Category;
use App\Models\Game;
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
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Category::class);
            $table->foreignIdFor(Game::class)->after('title')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->foreignIdFor(Category::class);
            $table->dropConstrainedForeignIdFor(Game::class);
        });
    }
};
