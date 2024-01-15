<?php
use App\Models\Lobby;
use App\Models\Set;
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lobbies') }}
        </h2>
        <h3 class="text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{route('lobbies/create')}}">{{__('Create Lobby')}}</a>
        </h3>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl"> 
                    <?php
                    ?>   
                    <form name="add-lobby-post-form" id="add-lobby-post-form" method="post" action="{{url('lobbies/store')}}">
                        @csrf
                         <div class="form-group">
                           <label for="name">Name</label>
                           <input type="text" id="name" name="name" class="form-control" required="">
                         </div>
                         <div class="form-group">
                            <label for="max_players">Max Players</label>
                            <input type="text" id="max_players" name="max_players" class="form-control" required="">
                          </div>
                          <div class="form-group">
                            <label for="max_rounds">Max Rounds</label>
                            <input type="text" id="max_rounds" name="max_rounds" class="form-control" required="">
                          </div>
                          <div class="form-group">
                            <label for="sets">Sets(provide sets reference codes like that: CODE, CODE)</label>
                            <input type="text" id="sets" name="sets" class="form-control" required="">
                          </div>
                         <button type="submit" class="btn btn-primary">Submit</button>
                       </form>       
                </div>
            </div>
    </div>
</x-app-layout>
