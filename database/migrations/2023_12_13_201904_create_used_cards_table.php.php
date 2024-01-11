<?php

use App\Models\Card;
use App\Models\Lobby;
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
        Schema::create('used_cards', function (Blueprint $table) {
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
        Schema::dropIfExists('used_cards');
    }
};
