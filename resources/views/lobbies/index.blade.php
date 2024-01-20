@extends('layout')
@section('content')
<section>
    <div class="empty-div"></div>
        <div class="lobby">
            <div id="menu">
                <p id="text">Lista pokoi</p>
                <a href="{{route('lobbies')}}"><button>Odśwież</button></a>
                <a href="{{route('lobbies/create_temp')}}"><button>Stwórz pokój</button></a>
            </div>
            <div id="rooms">
                <div id="search">
                    <input type="text" placeholder="Wyszukaj nowe lobby">
                    <span>
                        <?php
                            $players = 0;
                            foreach($lobbies as $lobby)
                            {
                                $players += $lobby->countCurrentPlayers();
                            }
                        ?>
                        {{$players}} graczy w {{count($lobbies)}} pokojach</span>
                </div>
                <div id="room-info">
                    @if(count($lobbies) == 0)
                    <h2>No lobbies found</h2>
                    @else
                    <table>
                        <tr>
                            <th>Nazwa</th>
                            <th>Liczba graczy</th>
                            <th>Hasło</th>
                            <th>Host</tr>
                        </tr>
                        @foreach($lobbies as $lobby)
                            @if($lobby->card_id != 0)
                                <tr>
                                    <td><a href="{{ route('lobby', ['id' => $lobby->id]) }}">{{ $lobby->name }}</a></td>
                                    <td>{{ $lobby->countCurrentPlayers() }}/{{ $lobby->max_players }}</td>
                                    <td>
                                        @if($lobby->hasPassword())
                                            Tak
                                        @else
                                            Nie
                                        @endif
                                    </td>
                                    <td>{{$lobby->owner->name}}</td>
                                </tr>
                            @endif                                         
                        @endforeach
                    </table>   
                    @endif  
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection