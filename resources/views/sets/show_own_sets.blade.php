@extends('layout')
@section('content')
<div class="empty-div"></div>
<div id="main-content">
    <h1>Twoje talie:</h1>
</div>
<div id="left">
    <a href="{{route('sets/create')}}"><button >Stwórz nową talię +</button></a>
    <a href="{{route('sets/user=', ['id' => auth()->user()->id])}}"><button>Twoje talie</button></a>
    <a href="{{route('sets/popular')}}"><button>Popularne</button></a>
    <a href="{{route('sets/recent')}}"><button>Ostatnio Dodane</button></a>
    <a href="{{route('sets/all')}}"><button>Wszystkie</button></a>
</div>
<div class="right">
<section>
    @if(count($sets) == 0)
    <h2>Looks, like you haven't created any sets yet!</h2>
    <h2>Click here to create one!</h2>
    @else
    <div class="card-container">
        @foreach($sets as $set)
        <a href="{{route('sets/edit', ['id' => $set->id])}}">
            <div class="card-container">
                <div class="card">{{$set->name}}, KOD: {{$set->reference_code}}</div>
                <div class="card"></div>   
                </div>
        </a> 
        @endforeach
    </div>
    @endif 
</section>
</div>
</div>
@endsection
