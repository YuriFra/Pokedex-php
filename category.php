<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//number of pages = total nr of pokemons / 20 per page
$totalPages = ceil(964 / 20);
//current page
$currentPage = (isset($_GET['page'])) ? ($_GET['page']) : 1;

$display20 = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon?offset='.(($currentPage - 1) * 20).'&limit=20'), true);

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="pokedex">
    <meta name="keywords" content="pokemon index">
    <meta name="author" content="Yuri">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/category.css" type="text/css">
    <title>Pokedex index</title>
</head>
<body>

<div class="container">
    <!-- Header -->
    <header>
        <a id="linkIndex" href="index.php">Home</a>
        <img src="img/pokemon.png" id="pokemon" alt="pokeTitle">
    </header>

    <!-- Main -->
    <main>
        <!-- pagination -->
        <nav aria-label="display 20 pokemon">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="category.php?page=1" aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="category.php?page=<?php
                    if ($currentPage > 1) {
                        echo $currentPage - 1;
                    } else {
                        echo 1;
                    } ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="category.php?page=<?php echo $currentPage.'">'.$currentPage ?></a></li>
                <li class="page-item">
                    <a class="page-link" href="category.php?page=<?php
                    if ($currentPage < $totalPages) {
                        echo $currentPage + 1;
                    } else {
                        echo $totalPages;
                    } ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="category.php?page=<?php echo $totalPages ?>" aria-label="Last">
                        <span aria-hidden="true">&raquo;&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <section id="pokeInfo" class="container">
            <div class="row mb-5">
                <?php foreach($display20['results'] as $value) {
                    $url20 = json_decode(file_get_contents($value['url']), true);
                    echo '<div id="info" class="col-3 text-center py-2 my-2"><div id="back"><img src="' . $url20['sprites']['front_default'] . '" alt="No image"><div><a id="linkPoke" href="index.php?pokeId='.$value['name'].'">'.ucfirst($value['name']).'</a></div></div></div>';
                } ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>