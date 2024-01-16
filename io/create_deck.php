<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/create_deck.css">

        <script type="text/javascript">
            document.querySelectorAll('.add-btn').forEach(button => {
            button.addEventListener('click', () => {
                var input = document.querySelector('.card-text-input').value; // Get the user input
                var cardsList = button.nextElementSibling; // Get the cards list element
                var cardItem = document.createElement('div'); // Create a card item element
                cardItem.className = 'card-item'; // Add the class name
                var cardText = document.createElement('span'); // Create a span element
                cardText.className = 'card-text'; // Add the class name
                cardText.textContent = input; // Add the user input as text
                cardItem.appendChild(cardText); // Append the span to the card item
                var removeBtn = document.createElement('button'); // Create a button element
                removeBtn.className = 'remove-btn'; // Add the class name
                removeBtn.textContent = 'X'; // Add the X as text
                cardItem.appendChild(removeBtn); // Append the button to the card item
                cardsList.appendChild(cardItem); // Append the card item to the cards list
                document.querySelector('.card-text-input').value = ''; // Clear the input value
                removeBtn.addEventListener('click', function() { // Add a click event listener to the remove button
                cardsList.removeChild(cardItem); // Remove the card item from the cards list
                });
            });
            });
            console.log("Server started and collections cleared."); // Use lowercase 'e' in consol

        </script>
    </head>
    <body>
        <?php
            include 'navbar.php';
        ?>

<div class="empty-div"></div>


<div id="editor">
    <div id="top-bar">
        <div id="deck-name-container">
            <span id="deck-name">Nazwa talii</span>
            <input type="text" id="deck-name-input" placeholder="Wpisz nazwę talii...">
        </div>
        <span id="editor-title">Edytor Talii</span>
        <button id="save-btn">ZAPISZ</button>
    </div>


    <div id="cards-section">
        <div class="card-container">
            <img src="img/white_card.png" alt="">
            <input type="text" class="card-text-input white" placeholder="Wpisz tekst...">
            <button class="add-btn">Dodaj</button>
            <div class="cards-list">
                <!-- Append user inputs here -->
            </div>
        </div>

        <div class="card-container">
            <img src="img/black_card.png" alt="">
            <input type="text" class="card-text-input black" placeholder="Wpisz tekst...">
            <button class="add-btn">Dodaj</button>
            <div class="cards-list">
                <!-- Append user inputs here -->
            </div>
        </div>
    </div>
</div>


    </body>
</html>