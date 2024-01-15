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
                        <form name="edit-set-post-form" id="edit-set-post-form" method="post" action="{{url('sets/update/'.$set->id)}}">
                            @csrf
                             <div class="form-group">
                               <label for="name">Name</label>
                               <input type="text" id="name" name="name" class="form-control" required="" value="{{$set->name}}">
                             </div>
                             <div class="form-group">
                                <p>Add new card</p>
                                <div class="form-group">
                                    <label for="card-type">Card Type</label>
                                    <select id="card-type" name="card_type">
                                        <option value="Answer Card">Answer Card</option>
                                        <option value="Question Card">Question Card</option>
                                    </select>
                                </div>
                                  <div class="form-group">
                                    <label for="card-content">Card content, remember to put __ in Question Cards!</label>
                                    <textarea id="card-content" name="card_content"></textarea>
                                  </div>
                              </div>
                              @foreach($set->getAllCardsType(True) as $card)
                              <div class="form-group-m">
                                <p>Edit card</p>
                                <div class="form-group">
                                    <label for="card-type{{$card->id}}">Card Type</label>
                                    <select id="card-type{{$card->id}}" name="card_type{{$card->id}}">
                                        <option value="Answer Card">Answer Card</option>
                                        <option value="Question Card" selected>Question Card</option>
                                    </select>
                                </div>
                                  <div class="form-group">
                                    <label for="card-content{{$card->id}}">Card content</label>
                                    <textarea id="card-content{{$card->id}}" name="card_content{{$card->id}}">{{$card->card_description}}</textarea>
                                  </div>
                                  <button type="button" onclick="removeCard('{{ $set->id }}', '{{ $card->id }}')">Remove Card</button>
                              </div>
                              @endforeach
                              @foreach($set->getAllCardsType(False) as $card)
                              <div class="form-group-m">
                                <p>Edit card</p>
                                <div class="form-group">
                                    <label for="card-type{{$card->id}}">Card Type</label>
                                    <select id="card-type{{$card->id}}" name="card_type{{$card->id}}">
                                        <option value="Answer Card" selected>Answer Card</option>
                                        <option value="Question Card">Question Card</option>
                                    </select>
                                </div>
                                  <div class="form-group{{$card->id}}">
                                    <label for="card-content{{$card->id}}">Card content</label>
                                    <textarea id="card-content{{$card->id}}" name="card_content{{$card->id}}">{{$card->card_description}}</textarea>
                                  </div>
                                  <button type="button" onclick="removeCard('{{ $set->id }}', '{{ $card->id }}')">Remove Card</button>
                              </div>
                              @endforeach
                             <button type="submit" class="btn btn-primary">Submit</button>
                           </form>     
                </div>
            </div>
    </div>
</x-app-layout>
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
                        var cardElement = document.getElementById(`card-type${cardId}`).closest('.form-group-m');
                        cardElement.parentNode.removeChild(cardElement);
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
