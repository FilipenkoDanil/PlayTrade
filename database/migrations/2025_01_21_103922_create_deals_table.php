<?php

use App\Models\Offer;
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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->double('price', 8, 2)->default(0);
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignIdFor(Offer::class)->constrained();
            $table->string('offer_title')->nullable();
            $table->string('offer_description')->nullable();
            $table->json('offer_attributes')->nullable();
            $table->json('offer_server')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
