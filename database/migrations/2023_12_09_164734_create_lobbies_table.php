<?php

use App\Models\Card;
use App\Models\User;
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
        Schema::create('lobbies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('max_players')->default(10);
            $table->integer('current_round')->default(0);
            $table->integer('max_rounds')->default(8);
            $table->string('password')->default('');
            $table->bigInteger('round_timer')->default(30000);
            $table->bigInteger('time_remaining')->default(30000);
            $table->foreignIdFor(Card::class);
            $table->foreignIdFor(User::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lobbies');
    }
};
