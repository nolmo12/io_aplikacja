<?php

use App\Models\Lobby;
use App\Models\Set;
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
        Schema::create('lobby_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Lobby::class)->constrained();
            $table->foreignIdFor(Set::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lobby_sets');
    }
};
