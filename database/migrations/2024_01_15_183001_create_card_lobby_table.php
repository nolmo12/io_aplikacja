<?php

use App\Models\Card;
use App\Models\Lobby;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_lobby', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Card::class)->constrained();
            $table->foreignIdFor(Lobby::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_lobby');
    }
};
