<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lobbies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(count($lobbies) == 0)
                    <h2>No lobbies found</h2>
                    @else
                        @foreach($lobbies as $lobby)
                        <a href="{{ route('lobby', ['id' => $lobby->id]) }}" class="game">
                            <div style="color: white">
                                {{ $lobby->name }}, Players: {{ $lobby->countCurrentPlayers() }}/{{ $lobby->max_players }}, Rounds: {{ $lobby->current_round }}/{{ $lobby->max_rounds }}
                            </div>
                        </a>                        
                        @endforeach
                    @endif  
                </div>
            </div>
    </div>
</x-app-layout>
