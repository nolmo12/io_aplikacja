    @extends('layout')
    @section('content')
    <div class="empty-div"></div>

    <div id="editor">
        <div id="top-bar">
            @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
          @endif  
            <form name="edit-set-post-form" id="edit-set-post-form" method="post" action="{{url('sets/update/'.$set->id)}}">
                @csrf
            <div id="deck-name-container">
                  <label for="deck-name-input">Nazwa Talii</label>
                <input type="text" id="deck-name-input" name="name" required="" value="{{$set->name}}">
            </div>
            <span id="editor-title">Edytor Talii</span>
        </div>

        <div id="cards-section">
            <section class="cards-list">
                <div class="card-container">
                    <div class="card-white">
                        <input class="card-input" name="new_white_card" value = "Wpisz tekst">
                        <button class="remove-button">Dodaj Kartę</button>
                    </div>
                </div>

                <div class="card-container">
                    <div class="card-black">
                        <input class="card-input" name="new_black_card" value = "Wpisz tekst">
                        <button class="remove-button">Dodaj Kartę</button>
                    </div>
                </div>
            </section>
            <section>
        </section>
        </div>
    </div>
    <div id="added-cards">
        <div id="white-cards-section" class="cards-section">
            <section class="cards-list">
                    @foreach($set->getAllCardsType(False) as $card)
                    <div class="card-container-small">
                    <div class="card-white" id="card{{$card->id}}">
                        <input class="card-input" value = "{{$card->card_description}}">
                        <button class="delete-button" onclick="removeCard('{{ $set->id }}', '{{ $card->id }}')">Usuń kartę</button>
                    </div>
                </div>
                @endforeach
            </section>
        </div>
        <div id="black-cards-section" class="cards-section">
            <section class="cards-list">
                    @foreach($set->getAllCardsType(True) as $card)
                    <div class="card-container-small">
                    <div class="card-black" id="card{{$card->id}}">
                        <input class="card-input" value = "{{$card->card_description}}">
                        <button class="delete-button" onclick="removeCard('{{ $set->id }}', '{{ $card->id }}')">Usuń kartę</button>
                    </div>
                </div>
                @endforeach
            </section>
        </div>
    </div>
<script>
    function removeCard(setId, cardId) {
        var confirmRemove = confirm("Are you sure you want to remove this card?");

        if (confirmRemove) {
            var xhr = new XMLHttpRequest();
            xhr.open('DELETE', '/sets/' + setId + '/cards/' + cardId, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // Remove the card from the UI
                        var cardElement = document.getElementById(`card${cardId}`);
                        cardElement.remove();
                    } else {
                        // Handle the error case
                        console.error('Error removing card:', xhr.responseText);
                    }
                }
            };
            xhr.send();
        }
    }
</script>
@endsection