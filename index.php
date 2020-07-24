<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (isset($_GET['pokeId'])) {
    $pokeInfo = @file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $_GET['pokeId']);
    if (empty($pokeInfo)) {
        echo '<script type="text/javascript">alert("Invalid name or ID");
window.location.href="/";</script>';
    } else {
        $pokeArr = json_decode($pokeInfo, true);
        $pokeName = $pokeArr['name'];
        $pokeId = $pokeArr['id'];
        // get previous evolution
        $pokeChild = file_get_contents('https://pokeapi.co/api/v2/pokemon-species/' . $pokeName);
        $childArr = json_decode($pokeChild, true);
        // next get evolutions
        $pokeParent = file_get_contents($childArr['evolution_chain']['url']);
        $parentArr = json_decode($pokeParent, true);
        $firstPoke = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $parentArr['chain']['species']['name']), true);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="pokedex">
    <meta name="keywords" content="pokemon index">
    <meta name="author" content="Yuri">
    <title>Pokedex</title>
</head>
<body>

<div class="container">
    <!-- Header -->
    <header>
        <a id="linkCat" href="category.php">Category</a>
        <img src="img/pokemon.png" id="pokemon" alt="pokeTitle">
    </header>

    <!-- Main -->
    <main>
        <section class="input-field">
            <form method="get">
                <input id="input" type="text" autocomplete="off" name="pokeId" placeholder="ID or Name Pokemon">
                <button id="button" aria-label="startSearch" type="submit" class="btn btn-danger">I choose you</button>
            </form>
        </section>
        <section id="background">
            <div id="info">
                <?php if (isset($_GET['pokeId'])) echo '<div id="first"><div id="id">ID: ' . $pokeId . ' - ' . ucfirst($pokeName) . '</div>
           <div id="sprite"><img src="' . $pokeArr['sprites']['front_default'] . '" alt="sprite">
           <img src="' . $pokeArr['sprites']['back_default'] . '"></div></div>'; ?>
                <div id="second">
                    <?php if (isset($_GET['pokeId'])) {
                        $movesArr = array_slice($pokeArr['moves'],  0,4);
                        foreach ($movesArr as $value) {
                            echo '<div id="moves">' . $value['move']['name'] . '</div>';
                        }
                    } ?>
                </div>
                <div id="evolution">
                    <?php if (isset($_GET['pokeId']))
                        if ($parentArr['chain']['evolves_to'] === []) {
                            echo '<p>No evolution</p>';
                        } elseif (count($parentArr['chain']['evolves_to']) >= 1) {
                            echo '<div id="evo"><img id="sprites" src="' . $firstPoke['sprites']['front_default'] . '" alt="sprite"><div>' . ucfirst($parentArr['chain']['species']['name']) . '</div></div>';
                            foreach ($parentArr['chain']['evolves_to'] as $value) {
                                $pokeList = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $value['species']['name']), true);
                                echo '<div id="evo"><img id="sprites" src="' . $pokeList['sprites']['front_default'] . '" alt="sprite"><div>' . ucfirst($value['species']['name']) . '</div></div>';
                                if (count($parentArr['chain']['evolves_to'][0]['evolves_to']) >= 1) {
                                    foreach ($parentArr['chain']['evolves_to'][0]['evolves_to'] as $newValue) {
                                        $nextPoke = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $newValue['species']['name']), true);
                                        echo '<div id="evo"><img id="sprites" src="' . $nextPoke['sprites']['front_default'] . '" alt="sprite"><div>' . ucfirst($newValue['species']['name']) . '</div></div>';;
                                    }
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
<style>
    <?php include 'css/style.css'; ?>
</style>