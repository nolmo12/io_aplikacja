<?php
use App\Models\User;
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sets') }}
        </h2>
        <h3 class="text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{route('sets/create') }}">{{__('Create new set')}}</a>
        </h3>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(count($sets) == 0)
                    <h2>Looks, like you haven't created any sets yet!</h2>
                    <h2>Click here to create one!</h2>
                    @else
                        @foreach($sets as $set)
                        <p style="color:white"><a href="{{route('sets/edit', ['id' => $set->id])}}">{{$set->name}}</a>  </p>              
                        @endforeach
                    @endif  
                </div>
            </div>
    </div>
</x-app-layout>
