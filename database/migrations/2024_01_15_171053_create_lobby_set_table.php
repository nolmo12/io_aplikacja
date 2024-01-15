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
        Schema::create('lobby_set', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lobby_id')->constrained();
            $table->foreignId('set_id')->constrained();
            // Add any additional columns you need in the pivot table
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lobby_set');
    }
};
