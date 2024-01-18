@extends('layout')
@section('content')
<div class="empty-div"></div>
<div id="main-content">
    <h1>Twoje talie:</h1>
</div>

<section>
    @if(count($sets) == 0)
    <h2>Looks, like you haven't created any sets yet!</h2>
    <h2>Click here to create one!</h2>
    @else
        @foreach($sets as $set)
        <a href="{{route('sets/edit', ['id' => $set->id])}}">
            <div class="card-container">
                <div class="card">{{$set->name}}, KOD: {{$set->reference_code}}</div>
                <div class="card"></div>   
                </div>
        </a> 
        @endforeach
    @endif 
</section>
@endsection
