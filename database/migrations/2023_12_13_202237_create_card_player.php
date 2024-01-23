<?php

use App\Models\Card;
use App\Models\Player;
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
        Schema::create('card_player', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class)->constrained();
            $table->foreignIdFor(Card::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_player');
    }
};
