<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ URL::asset('css/winner_endgame.css') }}">
    <title>Strona Użytkownika</title>
</head>
<body>

<div class="container">
    <h1>{{$user_name}}</h1>
    
</div>
<div class="container">
        <h3>Wygrał z ilością punktów:</h3>
        <div class="container">
            <h1>{{ $points }}</h1>
        </div>
</div>
<div class="container">
    <a href="{{route('lobbies')}}"><button >Wróć do menu głównego </button></a>
</div>
</body>

</html>
<script>
    console.log("{{request('points')}}");
</script>