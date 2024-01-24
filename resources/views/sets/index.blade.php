<?php
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
?>
@extends('layout')
@section('content')
<div class="empty-div">
</div>
<div id="layout">
    <div id="left">
        <a href="{{route('sets/create')}}"><button >Stwórz nową talię +</button></a>
        <a href="{{route('sets/user=', ['id' => auth()->user()->id])}}"><button>Twoje talie</button></a>
        <a href="{{route('sets/popular')}}"><button>Popularne</button></a>
        <a href="{{route('sets/recent')}}"><button>Ostatnio Dodane</button></a>
        <a href="{{route('sets/all')}}"><button>Wszystkie</button></a>
    </div>
    <div class="right">
        @isset($title)  
        <h1 style="font-size:200%; color:white">{{$title}}</h1>
@endisset
        <section>
            <div class="card-container">
                @isset($sets)
                @foreach($sets as $set)
                <a href="{{ route('set', ['id' => $set->id]) }}">
                    <div class="card-container">
                        <div class="card">{{$set->name}}, KOD:{{$set->reference_code}}</div>
                        <div class="card"></div>   
                    </div>
                </a>
                @endforeach
                @endisset
            </div>
            </section>
</div>
</div>
@endsection
