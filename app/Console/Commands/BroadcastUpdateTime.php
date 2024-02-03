<?php

namespace App\Console\Commands;

use App\Models\Lobby;
use App\Events\LobbyUpdateTime;
use Illuminate\Console\Command;
use App\Events\LobbyTimeReachedZero;
use App\Http\Controllers\LobbyController;

class BroadcastUpdateTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:broadcast-update-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast the LobbyUpdateTime event every second';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lobbies = Lobby::all();
    
        foreach ($lobbies as $lobby) {
            if ($lobby->time_remaining > 0)
            {
                if ($lobby->time_remaining - 1000 <= 0) 
                {
                    $lobby->time_remaining = 0;
                }
                else
                {
                    $lobby->time_remaining -= 1000;
                }
    
                $lobby->save();
                broadcast(new LobbyUpdateTime($lobby->id, $lobby->time_remaining));
    
                if ($lobby->time_remaining === 0)
                {
                    LobbyController::checkIfCardsOnTable($lobby->id);
                }
            }
        }
    }
}
