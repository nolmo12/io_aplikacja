<?php
use App\Models\User;
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Set: ')}}{{$set->name}}, REFERENCE CODE: {{$set->reference_code}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(count($set->getAllCards()) == 0)
                    <h2>No cards found</h2>
                    @else
                        @foreach($set->getAllCardsType(True) as $card)
                        <a href="{{ route('set', ['id' => $set->id]) }}" class="game">
                            <div style="color: white">
                                Card Desciption Name: {{$card->card_description}}
                            </div>
                        </a>                        
                        @endforeach
                        @foreach($set->getAllCardsType(False) as $card)
                        <a href="{{ route('set', ['id' => $set->id]) }}" class="game">
                            <div style="color: white">
                                Card Desciption Name: {{$card->card_description}}
                            </div>
                        </a>                        
                        @endforeach
                    @endif 
                </div>
            </div>
    </div>
</x-app-layout>
