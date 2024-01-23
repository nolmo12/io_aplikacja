@extends('layout')
@section('content')
@if(auth()->user()->id === $lobby->owner->id && $lobby->card_id === 0)
<div style="background-color: #20222a; height:100vh">
<div class="empty-div"></div>
<form name="add-lobby-post-form" id="add-lobby-post-form" method="post" action="{{url('lobbies/store/'.$lobby->id)}}">
  @csrf 
<div id="game-room">
  <div id="lobby-title">
    <input id="lobby-name" value="{{$lobby->name}}" name="lobby_name" style="border: none; background: none; outline: none;">
  </div>
  <div id="players-list">
    <ul>
      <h2>Lista graczy:</h2>
      @foreach($lobby->getCurrentPlayers() as $player)
        @if($player->user->name === auth()->user()->name)
        <a href="{{route('profile/', ['id' => $player->user->id])}}"><li>{{$player->user->name}}</li></a>
        @else
        <a href="{{route('profile/', ['id' => $player->user->id])}}"><li>{{$player->user->name}}</a><button type="button" onclick="removePlayer({{$lobby->id}}, {{$player->id}})">Usuń</button></li>
        @endif
      @endforeach
      </ul>
  </div>
  
  <div id="game-content">
    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
  @endif  
      <div id="game-settings"> 
          <label for="turn-time">Czas tury:</label>
          <input type="number" id="turn-time" min="1" max="60" name ="turn_timer" value="{{$lobby->round_timer / 1000}}">
          
          <label for="rounds-number">Liczba rund:</label>
          <input type="number" id="rounds-number" name = "max_rounds" min="1" max="10" value="{{$lobby->max_rounds}}">
          <label for="players-number">Maksymalna Liczba Graczy:</label>
          <input type="number" id = "players-number" name = "max_players" min="3" max="20" value="{{$lobby->max_players}}">
      </div>

      <div id='deck-section'>
          <div id='deck-section-add'>
            <p class="add-deck-text">Dodaj Talie:</p>
              <button type="button" onclick="toggleInput()" class="back-button">+</button>
              <div id="inputContainer" class="hidden">
                  <input type="text" name="set_code" id="textInput" placeholder="Wpisz tekst">
              </div>
          </div>
          <div id="deck-section-decks">
              <!-- decki które dodał gracz -->
              
                  <section id = "sets">
                  @foreach($lobby->sets as $set)
                  <div class="card-container" id="card-sznycer{{$set->id}}">
                    <div class="card"> 
                      <p class="card-text">{{$set->name}}, KOD: {{$set->reference_code}}</p>
                      <button type="button" onclick="removeSet({{$lobby->id}}, {{$set->id}})" class="remove-button" >Usuń talię</button>
                    </div>
                      <div class="card"></div>    
                  </div>
                  @endforeach
                  </section>
              
          </div>
      </div>

      <div id="game-actions">
          Link do rozgrywki: 
          <a href="{{ route('lobby', ['id' => $lobby->id]) }}" id='game-link'>{{ route('lobby', ['id' => $lobby->id]) }}</a>

          <a href="{{route('lobbies')}}"><button type="button" class="back-button">< Powrót</button></a>
          <a href=""></a><button type="submit" class="start-button">Rozpocznij</button></a>
      
      </div>
  </div>
</div> 
</div>  
</form>

@elseif(auth()->user()->id != $lobby->owner->id && $lobby->card_id === 0)
<div style="background-color: #20222a">
<div class="empty-div"></div>
<form name="add-lobby-post-form" id="add-lobby-post-form" method="post" action="{{url('lobbies/join/'.$lobby->id)}}">
  @csrf 
  <input type="hidden" name="user" value="{{auth()->user()->id}}">
<div id="game-room">
  <div id="lobby-title">
    <input id="lobby-name" readonly value="{{$lobby->name}}" style="border: none; background: none; outline: none;">
  </div>
  <div id="players-list">
    <ul>
      <h2>Lista graczy:</h2>
      @foreach($lobby->getCurrentPlayers() as $player)
        <a href="{{route('profile/', ['id' => $player->user->id])}}"><li>{{$player->user->name}}</li></a>
      @endforeach
      </ul>
  </div>
  
  <div id="game-content">
    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
  @endif  
      <div id="game-settings"> 
          <label for="turn-time">Czas tury:</label>
          <input type="number" id="turn-time" min="1" max="60" value="{{$lobby->round_timer / 1000}}" readonly>
          
          <label for="rounds-number">Liczba rund:</label>
          <input type="number" id="rounds-number" min="1" max="10" value="{{$lobby->max_rounds}}" readonly>
          <label for="players-number">Maksymalna Liczba Graczy:</label>
          <input type="number" id="players-number" min="3" max="10" value="{{$lobby->max_players}}" readonly>
      </div>

      <div id='deck-section'>
          <div id="deck-section-decks">
              <!-- decki które dodał gracz -->
              
                  <section id = "sets">
                  @foreach($lobby->sets as $set)
                  <div class="card-container" id="card-sznycer{{$set->id}}">
                    <div class="card"> 
                      <p class="card-text">{{$set->name}}, KOD: {{$set->reference_code}}</p>
                    </div>
                      <div class="card"></div>    
                  </div>
                  @endforeach
                  </section>
              
          </div>
      </div>

      <div id="game-actions">
          Link do rozgrywki: 
          <a href="{{ route('lobby', ['id' => $lobby->id]) }}" id='game-link'>{{ route('lobby', ['id' => $lobby->id]) }}</a>

          <a href="{{route('lobbies')}}"><button type="button" class="back-button">< Powrót</button></a>
          @if($lobby->players()->where('user_id', auth()->user()->id)->first())
            @if(auth()->user()->id != $lobby->players()->where('user_id', auth()->user()->id)->first()->user_id)
              <a href=""></a><button type="submit" class="start-button">Dołącz</button></a>
            @else
              <a href=""></a><button type="button" onclick="removePlayer({{$lobby->id}}, {{App\Models\Player::where('user_id', auth()->user()->id)->first()->id}})" class="start-button">Opuść</button></a>
            @endif
          @else
          <a href=""></a><button type="submit" class="start-button">Dołącz</button></a>
          @endif
      
      </div>
  </div>
</div> 
</div>  
</form>
@else

<div class="top">
  &#8203;
  </div>
  
  <div id="game_layout">
      <div id="player-list">
          <h2>Lista Graczy</h2>
          <ul>
            @foreach($lobby->getCurrentPlayers() as $player)
              @if($player->is_judge == True)
              <ul class = "ul_judge">
                <li class="usr">{{$player->user->name}}</li><li class="pk">{{$player->current_points}}</li>
                </ul>
              @else
              <ul class = "ul_user">
              <li class="usr">{{$player->user->name}}</li><li class="pk" id = "player{{$player->id}}">{{$player->current_points}}</li>
              </ul>
              @endif
            @endforeach
          </ul>
          <div id = "round" style="color:white;">Rundy: {{$lobby->current_round}} / {{$lobby->max_rounds}}</div>
          <div id = "time" style="color:white;">Pozostały czas: {{$lobby->time_remaining / 1000}}</div>
      </div>
      <div id="center-area">
          <h2>Karty na stole</h2>
          <div id="game-cards">
          <div class="cards-section-a">
                      <section class="cards-list">
                          <div class="card-container-small">  
                              <div class="card-black">
                                  <p class="card-text">{{$lobby->getCurrentQuestionCard()->card_description}}</p>
                              </div>
                          </div>
                      </section>
              </div>
              
              <div class="cards-section-b">
                      <section class="cards-list" id = "table">
                        @php
                          $shuffledCards = $lobby->tableCards->shuffle();
                        @endphp
                        @foreach($shuffledCards as $played_card)
                          <div class="card-container-small">
                              <div class="card-white">
                                  <p class="card-text">{{$played_card->card->card_description}}</p>
                                  @if(auth()->user()->player->is_judge == True)
                                  <button data-card-id="{{ $played_card->id }}" class="delete-button">Wybierz</button>
                                  @endif
                              </div>
                          </div>
                          @endforeach
                      </section>
              </div>
          </div>
          <div>
              <h2>Twoje Karty</h2>
              <div id="white-cards-section" class="cards-section">
                  <section class="cards-list" id="user-cards">
                   @foreach(auth()->user()->player->cards as $card)
                    <div class="card-container-small" id = "{{$card->id}}">
                      <div class="card-white">
                          <p class="card-text">{{$card->card_description}}</p>
                          @if(auth()->user()->player->is_judge == False && $lobby->time_remaining > 0)
                            <button class="delete-button" onclick="chooseCart('{{$card->id}}')">Wybierz</button>
                          @endif
                      </div>
                    </div>
                    @endforeach
                  </section>
              </div>
          </div>
      </div>
  </div>

@endif
<script>
  function toggleInput() {
      var inputContainer = document.getElementById("inputContainer");

      // Jeśli div jest ukryty, pokaż go; jeśli jest widoczny, ukryj go
      inputContainer.classList.toggle("hidden");
  }
  function removeSet(lobbyId, setId)
  {
            var xhr = new XMLHttpRequest();
            xhr.open('DELETE', '/lobbies/' + lobbyId + '/sets/' + setId, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200)
                     {
                      var setElement = document.getElementById("card-sznycer" + setId);
                        setElement.remove();
                    }
                    else 
                    {
                        // Handle the error case
                        console.error('Error removing set:', xhr.responseText);
                    }
                }
            };
            xhr.send();
  }

  function removePlayer(lobbyId, playerId)
  {
            var xhr = new XMLHttpRequest();
            xhr.open('DELETE', '/lobbies/' + lobbyId + '/player/' + playerId, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200)
                     {
                    }
                    else 
                    {
                        // Handle the error case
                        console.error('Error removing player:', xhr.responseText);
                    }
                }
            };
            xhr.send();
  }

  function updateSetsContainer(sets) 
  {
    // Clear the current content of the sets container
    $('#sets').empty();

    // Iterate through the updated sets and append them to the container
    sets.forEach(function(set) {
        if({{auth()->user()->id}} === {{$lobby->owner->id}})
        {
          var cardContainer = $('<div class="card-container" id="card-sznycer'+ set.id + '"></div>');
        var card = $('<div class="card"><p class="card-text">' + set.name + ', KOD: ' + set.reference_code + '</p> <button type="button" onclick="removeSet(' + {{$lobby->id}} + ', ' + set.id + ')" class="remove-button">Usuń deck</button></div><div class="card"></div>');
        cardContainer.append(card);
        }
        else
        {
          var cardContainer = $('<div class="card-container" id="card-sznycer'+ set.id + '"></div>');
        var card = $('<div class="card"><p class="card-text">' + set.name + ', KOD: ' + set.reference_code + '</p></div><div class="card"></div>');
        cardContainer.append(card);
        }
        $('#sets').append(cardContainer);
    });
  }

  function updatePlayers(players) {
    $('#players-list').empty();
    $('#players-list').append('Lista graczy:')

      var list = $('<ul></ul>');

      players.forEach(function (player) {
          // Make an AJAX request to fetch user data based on user_id
          $.ajax({
              url: '/user/' + player.user_id,
              type: 'GET',
              success: function (userData) {
                if({{auth()->user()->id}} === {{$lobby->owner->id}} && {{$lobby->owner->id}} != userData.id)
                {
                  var listElement = $('<li><a href="">' + userData.name + '</a><button type="button" onclick="removePlayer(' + {{$lobby->id}} + ', ' + player.id + ')"> Usuń </button></li>');
                  list.append(listElement);
                }
                else
                {
                  var listElement = $('<li><a href="">' + userData.name + '</a></li>');
                  list.append(listElement);
                }
                  $('#players-list').append(list);
              },
              error: function (error) {
                  console.error('Error fetching user data:', error);
              }
          });
      });
}

function updatePlayersDuringGame(players) {
    $('#players-list').empty();
    $('#players-list').append('Lista graczy')

      var list = $('<ul></ul>');

      players.forEach(function (player) {
          $.ajax({
              url: '/user/' + player.user_id,
              type: 'GET',
              success: function (userData) {
                if(player.is_judge)
                {
                  var listElement = $('<ul class = "ul_judge"> <li class="usr">' + userData.name + '</li><li class="pk">' + userData.name + '</li></ul>');
                }
                else
                {
                  var listElement = $('<ul class = "ul_user"><li class="usr">' + userData.name + '</li><li class="pk" id = "' + userData.name + '">{{$player->current_points}}</li></ul>');
                }
                  var listElement = $('<li><a href="">' + userData.name + '</a></li>');
                  list.append(listElement);
                  $('#players-list').append(list);
              },
              error: function (error) {
                  console.error('Error fetching user data:', error);
              }
          });
      });
}

function updateLobby(data)
{
  $('#lobby-name').val(data.lobby_name);
  $('#turn-time').val(data.round_timer / 1000);
  $('#rounds-number').val(data.max_rounds);
  $('#players-number').val(data.max_players);
}

function startLobby()
{
  location.reload()
}

function chooseCard(card)
{
  var lobby = {{$lobby->id}};

        $.ajax({
            type: 'GET',
            url: "{{ route('update-cards') }}", // Wrap the route function in quotes
            data: { card_id: card, lobby_id : lobby },
            success: function(data) {
            }
        });
}

function freezeRound(lobby_data)
{
  var lobby = {{$lobby->id}};

  $('#user-cards').find('button').remove();

  $.ajax({
    type: 'GET',
    url: "{{ route('check-if-cards-on-table', ['lobbyId' => $lobby->id]) }}",
  });
}

function refreshCards(player_id, card_id) {
    $.ajax({
        type: 'GET',
        url: "{{ url('card') }}/" + player_id + "/" + card_id,
        success: function(card) {
            var cardContainer = $('<div class="card-container-small"> <div class="card-white"> <p class="card-text">' + card.card_description + '</p></div></div>');
            $('#user-cards').append(cardContainer);
        },
        error: function(error) {
            console.error(error);
        }
    });
}

function revealCards(cards) {
    $('#table').empty();

    cards.forEach(function (card) {
        $.ajax({
            type: 'GET',
            url: "{{ url('card') }}/" + card.id,
            success: function (retrievedCard) {
                var cardContainer = $('<div class="card-container-small"> <div class="card-white"> <p class="card-text">' + retrievedCard.card_description + '</p></div></div>');

                @if(auth()->user()->player)
                  var isJudge = {{ auth()->user()->player->is_judge }};
                  if (isJudge) {
                    var button = $('<button data-card-id="' + card.id + '" class="delete-button">Wybierz</button>');
                    cardContainer.find('.card-white').append(button);
                  }
                @endif

                $('#table').append(cardContainer);
            },
            error: function (error) {
                console.error(error);
            }
        });
    });
}

function updatePoints(playerId, points)
{
  $('#player'+playerId).text(points);
}

function showWinningCard()
{

}
function selectNextJudge(player_id)
{
  var lobby = {{$lobby->id}};
  $.ajax({
            type: 'GET',
            url: "{{ route('next-judge') }}", // Wrap the route function in quotes
            data: { lobby_id : lobby },
            success: function(players){
              updatePlayersDuringGame(players)
            }
        });
}
function clearTable()
{
  var lobby = {{$lobby->id}};
  $('#table').empty();
  $.ajax({
            type: 'GET',
            url: "{{ route('clear-table') }}", // Wrap the route function in quotes
            data: { lobby_id : lobby },
        });
}

function removeCard(card)
{
  $('#'+card).remove();
}

$(document).ready(function () {
    var lobby = {{$lobby->id}};
    var isEventAllowed = true;

    $('.delete-button').on('click', function () {
        if (isEventAllowed) {
            var cardId = $(this).data('card-id');

            // Send an AJAX request to the server
            $.ajax({
                type: 'GET',
                url: '{{ route('choose-winning-card') }}',
                data: { card_id: cardId, lobby_id: lobby },
                beforeSend: function () {
                    // Disable the button during the AJAX request
                    isEventAllowed = false;
                },
                complete: function () {
                    // Enable the button after the request is complete
                    setTimeout(function () {
                        isEventAllowed = true;
                    }, 5000); // Adjust the cooldown period as needed
                },
            });
        }
    });
});


  var delayTimer = 250;

  $('#turn-time').on('input', function() {
      var lobby = {{$lobby->id}};
      var query = {
        "lobby-name": $('#lobby-name').val(),
        "turn-time": $(this).val(),
        "max-rounds": $('#rounds-number').val(),
        "max-players": $('#players-number').val()
       };

       clearTimeout(delayTimer);

       delayTimer = setTimeout(function(){
        $.ajax({
            type: 'GET',
            url: "{{ route('lobby/data/update/') }}", // Wrap the route function in quotes
            data: { query: query, lobby_id : lobby },
            success: function(data) {
                console.log(data);
                // Handle the data returned from the server
                //$('#searchResults').html(data);
            }
        });
      }, 250)
  });

  $('#lobby-name').on('input', function() {
      var lobby = {{$lobby->id}};
      var query = {
        "lobby-name": $(this).val(),
        "turn-time": $('#turn-time').val(),
        "max-rounds": $('#rounds-number').val(),
        "max-players": $('#players-number').val()
       };

       clearTimeout(delayTimer);

       delayTimer = setTimeout(function(){
        $.ajax({
            type: 'GET',
            url: "{{ route('lobby/data/update/') }}", // Wrap the route function in quotes
            data: { query: query, lobby_id : lobby },
            success: function(data) {
            }
        });
       }, 250)
  });

  $('#rounds-number').on('input', function() {
      var lobby = {{$lobby->id}};
      var query = {
        "lobby-name": $('#lobby-name').val(),
        "turn-time": $('#turn-time').val(),
        "max-rounds": $(this).val(),
        "max-players": $('#players-number').val()
       };

       clearTimeout(delayTimer);

       delayTimer = setTimeout(function(){
        $.ajax({
            type: 'GET',
            url: "{{ route('lobby/data/update/') }}", // Wrap the route function in quotes
            data: { query: query, lobby_id : lobby },
            success: function(data) {
            }
        });
       }, 250)
  });

  $('#players-number').on('input', function() {
      var lobby = {{$lobby->id}};
      var query = {
        "lobby-name": $('#lobby-name').val(),
        "turn-time": $('#turn-time').val(),
        "max-rounds": $('#rounds-number').val(),
        "max-players": $(this).val()
       };

       clearTimeout(delayTimer);

       delayTimer = setTimeout(function(){
        $.ajax({
            type: 'GET',
            url: "{{ route('lobby/data/update/') }}", // Wrap the route function in quotes
            data: { query: query, lobby_id : lobby },
            success: function(data) {
            }
        });
       }, 250)
  });

  $('#textInput').on('input', function() {
        var lobby = {{$lobby->id}};
        var query = $(this).val();

        // Make an AJAX request to the server
        $.ajax({
            type: 'GET',
            url: "{{ route('search-set') }}", // Wrap the route function in quotes
            data: { query: query, lobby_id : lobby },
            success: function(data) {
                console.log(data);
                // Handle the data returned from the server
                //$('#searchResults').html(data);
            }
        });
    });

    // Enable pusher logging - don't include this in production
    //Pusher.logToConsole = true;

    var pusher = new Pusher('eae19956aee9c0efda16', {
      cluster: 'eu'
    });
    var channel = pusher.subscribe('lobby.' + {{$lobby->id}});

// Bind a callback function to handle lobby events
channel.bind('App\\Events\\SetUpdated', function(data) {
  updateSetsContainer(data.lobby_sets);
});

channel.bind('App\\Events\\PlayersUpdated', function(data) {
    updatePlayers(data.players);
});

channel.bind('App\\Events\\PlayerDeleted', function(data) {
  if(data.user_id == {{auth()->user()->id}})
  {
    alert("Zostałeś usunięty z lobby");
    location.reload();
  }
});

channel.bind('App\\Events\\LobbyUpdated', function(data) {
    updateLobby(data);
});

channel.bind('App\\Events\\LobbyStart', function(data) {
    startLobby();
});

channel.bind('App\\Events\\LobbyUpdateTime', function(data) {
    $('#time').text("Pozostały czas: " + data.time_remaining / 1000);
});

channel.bind('App\\Events\\LobbyTimeReachedZero', function(data) {
    freezeRound(data);
});

channel.bind('App\\Events\\PlayCards', function(data) {
  revealCards(data.cards);
});

channel.bind('App\\Events\\RemovePlayerCard', function(data) {
  removeCard(data.card_id);
});

channel.bind('App\\Events\\UpdateCards', function(data) {
  @if(auth()->user()->player)
    var currentPlayerId = {{ auth()->user()->player->id }};
    if (currentPlayerId == data.player_id) {
        refreshCards(data.player_id, data.card_id);
    }
  @endif
});

channel.bind('App\\Events\\UpdatePoints', function(data) {
  showWinningCard(data.card_id)
  updatePoints(data.player_id, data.points);
  clearTable();
  selectNextJudge(data.player_i);
});

</script> 
@endsection