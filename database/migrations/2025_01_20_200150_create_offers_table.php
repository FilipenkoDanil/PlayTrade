<?php

use App\Models\Category;
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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedInteger('amount')->default(0);
            $table->double('price', 8, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('auto_message')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignIdFor(Category::class)->constrained();
            $table->foreignId('seller_id')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
