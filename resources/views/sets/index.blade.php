<?php
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
?>
@extends('layout')
@section('content')
<div class="empty-div">
</div>
<div id="top">
    <a href="{{route('sets/create')}}""><button >Stwórz nową talię +</button></a>
    <a href="{{route('sets/user=', ['id' => auth()->user()->id])}}"><button>Twoje talie</button></a>
</div>
<section>
@foreach($popular_sets as $set)
<div class="card-container">
    <a href="{{ route('set', ['id' => $set->id]) }}">
        <div class="card-container">
            <div class="card">{{$set->name}}, KOD:{{$set->reference_code}}</div>
            <div class="card"></div>   
        </div>
    </a>
</div>
@endforeach
</section>
<div id="main-content">
        <h1>Ostatnio dodane</h1>
    </div>
<section>

    @foreach($recent_sets as $set)
    <div class="card-container">
        <a href="{{ route('set', ['id' => $set->id]) }}">
            <div class="card-container">
                <div class="card">{{$set->name}}, KOD:{{$set->reference_code}}</div>
                <div class="card"></div>   
            </div>
    </a>
    </div>
    @endforeach
</section>
<div class="main-content">
    <h1>Wszystkie talie</h1>
</div>
    <section>
    <?php
    DB::table('sets')->orderBy('used_times')->chunk(10, function (Collection $sets) {
    foreach ($sets as $set) {
        ?>
            <div class="card-container">
                <a href="{{ route('set', ['id' => $set->id]) }}">
                    <div class="card-container">
                        <div class="card">{{$set->name}}, KOD:{{$set->reference_code}}</div>
                        <div class="card"></div>   
                    </div>
            </a>
            </div>
        <?php
    }
    });
    ?>

</section>
@endsection
