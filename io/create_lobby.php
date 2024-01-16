<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/create_lobby.css">
    </head>
    <body>

    <?php
        include 'navbar.php';
    ?>
    <div class="empty-div"></div>

    <div id="game-room">
        <div id="lobby-title">Lobby - xyz</div>
        <div id="players-list">Lista graczy</div>
        
        <div id="game-content">
            <div id="game-settings">
                <label for="turn-time">Czas tury:</label>
                <input type="number" id="turn-time" min="1" max="10" value="1">
                
                <label for="rounds-number">Liczba rund:</label>
                <input type="number" id="rounds-number" min="1" max="10" value="1">
            </div>

            <div id='deck-section'>
                Dodaj Talie:
                <button onclick=''>+</button>
            </div>

            <div id="game-actions">
                Link do rozgrywki: 
                <a href="#" id='game-link'>https://xyzgames.com/invite/4fsc</a>

                <button onclick=''>< Powrót</button>
                <button onclick=''>Rozpocznij</button>
            </div>
        </div>
    </div>
    </script>
    </body>
</html>