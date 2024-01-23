<?php

use App\Models\Card;
use App\Models\Lobby;
use App\Models\Player;
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
        Schema::create('table_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Card::class)->constrained();
            $table->foreignIdFor(Lobby::class)->constrained();
            $table->foreignIdFor(Player::class)->nullable()->constrained(); // Nullable if not every card is associated with a player
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_cards');
    }
};
