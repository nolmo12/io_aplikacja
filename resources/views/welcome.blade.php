<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sznyc Games</title>
    <link rel="stylesheet" href="{{asset("css/welcome.css")}}">
</head>

<body>
    <header>
        <img src="{{asset("website_images/logo.png")}}" alt="Logo">
        @if (Route::has('login'))
        <livewire:welcome.navigation />
    @endif
    </header>

    <main >
        <section class="welcome-message">
        <h1>Cards Against Humanity</h1>

<!-- Main content -->
        <h3><p>
            Witaj na stronie Sznyc Games! Przygotuj się na niezapomnianą przygodę z grą "Cards Against Humanity".
            To innowacyjna gra karciana, w której kreatywność spotyka się z czarnym humorem. Doskonała zabawa
            gwarantowana dla Ciebie i Twoich przyjaciół!
        </h3></p>
        </section>
        <section class="welcome-message">   
        </section>

    </main>
</body>

</html>