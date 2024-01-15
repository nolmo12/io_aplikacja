<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="container mt-4">
                        @if(session('status'))
                          <div class="alert alert-success">
                              {{ session('status') }}
                          </div>
                        @endif    
                        <form name="add-set-post-form" id="add-set-post-form" method="post" action="{{url('sets/store')}}">
                            @csrf
                             <div class="form-group">
                               <label for="name">Name</label>
                               <input type="text" id="name" name="name" class="form-control" required="">
                             </div>
                             <button type="submit" class="btn btn-primary">Submit</button>
                           </form>     
                </div>
            </div>
    </div>
</x-app-layout>
