@extends('layout')
@section('content')

<div class="empty-div">
</div>
<div id="main-content">
    <h1>3 Najpopularniejsze talie:</h1>
</div>
<section>
    @foreach($sets as $set)
    <div class="card-container">
        <div class="card"> {{$set->name}}, KOD: {{$set->reference_code}}</div>
        <div class="card"></div> 
    </div>
    @endforeach
</section>
@endsection