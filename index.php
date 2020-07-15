<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (!empty($_GET['pokeId'])) {
    $pokeInfo = file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $_GET['pokeId']);
    $pokeArr = json_decode($pokeInfo, true);
}
$pokeName = $pokeArr['name'];
$pokeId = $pokeArr['id'];
//var_dump(json_decode($pokeInfo, true));
echo '<div id="first"><div id="id">ID: ' . $pokeId . '</div>
           <div id="name">' . ucfirst($pokeName) . '</div>
           <div id="sprite"><img src="' . $pokeArr['sprites']['front_default'] . '">
           <img src="' . $pokeArr['sprites']['back_default'] . '"></div></div>';
$movesArr = array_slice($pokeArr['moves'], 0, 4);
foreach ($movesArr as $value) {
    echo '<div id="moves">' . $value['move']['name'] . '</div>';
}

?>
<?php
// get childname from pokemon species api
$pokeChild = file_get_contents('https://pokeapi.co/api/v2/pokemon-species/' . $_GET['pokeId']);
$childArr = json_decode($pokeChild, true);
//var_dump($childArr);
if (!empty($childArr['evolves_from_species']['name'])) {
    echo '<div>Child: ' . ucfirst($childArr['evolves_from_species']['name']) . '</div>';
} else {
    echo '<div>' . ucfirst($childArr['name']) . ' is the baby</div>';
}

// get parent chain from url
$chainUrl = explode('/', $childArr['evolution_chain']['url']);
$chainId = $chainUrl[6];

// get poke evolutions from api
$pokeParent = file_get_contents('https://pokeapi.co/api/v2/evolution-chain/' . $chainId);
$parentArr = json_decode($pokeParent, true);
//var_dump($parentArr['chain']['evolves_to']);
if ($parentArr['chain']['evolves_to'] === []) {
    echo '<p>No evolution</p>';
} elseif (count($parentArr['chain']['evolves_to']) > 1) {
    $list = [];
    foreach ($parentArr['chain']['evolves_to'] as $value) {
        array_push($list, $value['species']['name']);
    }
    //display next evolution in array
    if (in_array($pokeName, $list)) {
        echo '<p>Parent: ' . ucfirst(next($list)) . '</p>';
    } else {
        //display first evolution in array
        echo '<p>Parent: ' . ucfirst(reset($list)) . '</p>';
    }
} elseif ($parentArr['chain']['evolves_to'][0]['evolves_to'][0]['species']['name'] === $pokeName) {
    echo '<p>No further evolution</p>';
} elseif ($parentArr['chain']['evolves_to'][0]['species']['name'] === $pokeName) {
    echo '<p>Parent: ' . ucfirst($parentArr['chain']['evolves_to'][0]['evolves_to'][0]['species']['name']) . '</p>';
} else {
    echo '<p>Parent: ' . ucfirst($parentArr['chain']['evolves_to'][0]['species']['name']) . '</p>';
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Ajax Pokédex">
    <meta name="keywords" content="Pokémon game">
    <meta name="author" content="Yuri">
    <title>Pokedex</title>
</head>
<body>

<div class="container">
    <!-- Header -->
    <header>
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
                <?php echo '<div id="first"><div id="id">ID: ' . $pokeId . '</div>
           <div id="name">' . ucfirst($pokeName) . '</div>
           <div id="sprite"><img src="' . $pokeArr['sprites']['front_default'] . '">
           <img src="' . $pokeArr['sprites']['back_default'] . '"></div></div>'; ?>
                <div id="second">
                    <?php $movesArr = array_slice($pokeArr['moves'],  0,4);
                    foreach ($movesArr as $value) {
                        echo '<div id="moves">' . $value['move']['name'] . '</div>';
                    } ?>
                </div>
                <div id="evolution">
                    <?php
                    if (!empty($childArr['evolves_from_species']['name'])) {
                        echo '<div>Child: ' . ucfirst($childArr['evolves_from_species']['name']) . '</div>';
                    } else {
                        echo '<div>' . ucfirst($childArr['name']) . ' is the baby</div>';
                    }

                    if ($parentArr['chain']['evolves_to'] === []) {
                        echo '<p>No further evolution</p>';
                    } elseif (count($parentArr['chain']['evolves_to']) > 1) {
                        $list = [];
                        foreach ($parentArr['chain']['evolves_to'] as $value) {
                            array_push($list, $value['species']['name']);
                        }
                        //display next evolution in array
                        if (in_array($pokeName, $list)) {
                            echo '<p>Parent: ' . ucfirst(next($list)) . '</p>';
                        } else {
                            //display first evolution in array
                            echo '<p>Parent: ' . ucfirst(reset($list)) . '</p>';
                        }
                    } elseif ($parentArr['chain']['evolves_to'][0]['evolves_to'][0]['species']['name'] === $pokeName) {
                        echo '<p>No further evolution</p>';
                    } elseif ($parentArr['chain']['evolves_to'][0]['species']['name'] === $pokeName) {
                        echo '<p>Parent: ' . ucfirst($parentArr['chain']['evolves_to'][0]['evolves_to'][0]['species']['name']) . '</p>';
                    } else {
                        echo '<p>Parent: ' . ucfirst($parentArr['chain']['evolves_to'][0]['species']['name']) . '</p>';
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
    <?php include 'style.css'; ?>
</style>