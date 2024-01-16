<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/lobby.css">
    </head>
    <body>
        <?php
            include 'navbar.php';
        ?>
        <section>
        <div class="empty-div"></div>
            <div class="lobby">
                <div id="menu">
                    <p id="text">Lista pokoi</p>
                    <button>Odśwież</button>
                    <button>Dołącz</button>
                    <a href="create_lobby.php"><button>Stwórz pokój</button></a>
                </div>

                <div id="rooms">
                    <div id="search">
                        <input type="text" placeholder="Wyszukaj nowe lobby">
                        <span>1 gracz w 1 pokojach</span>
                    </div>
                    <div id="room-info">
                        <table>
                            <tr>
                                <th>Nazwa</th>
                                <th>Liczba graczy</th>
                                <th>Hasło</th>
                            </tr>
                            <tr>
                                <td>Zapraszam wszystkich !!!!</td>
                                <td>1/10</td>
                                <td>Nie</td>
                            </tr>
                            <tr>
                                <td>Gramy na chilku!!!!</td>
                                <td>6/10</td>
                                <td>Tak</td>
                            </tr>
                        
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </body>
</html>