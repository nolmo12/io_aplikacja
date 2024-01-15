<?php
use App\Models\User;
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sets') }}
        </h2>
        <h3 class="text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('sets/user=', ['id' => Auth::user()->id]) }}">{{__('Browse your sets')}}</a>
        </h3>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(count($sets) == 0)
                    <h2>No sets found</h2>
                    @else
                        @foreach($sets as $set)
                        <a href="{{ route('set', ['id' => $set->id]) }}" class="game">
                            <div style="color: white">
                                Set Name: {{$set->name}}, REFERENCE CODE: {{$set->reference_code}}
                            </div>
                        </a>                        
                        @endforeach
                    @endif  
                </div>
            </div>
    </div>
</x-app-layout>
