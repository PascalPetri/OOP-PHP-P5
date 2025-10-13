<?php

// name pascal
// date 13-10-2025

require_once "src/ProductList.php";
require_once "src/Music.php";
require_once "src/Film.php";
require_once "src/Game.php";

// ----------------- MUSIC -----------------
$music1 = new Music("Greatest Hits", 10, 21, "Best songs collection", ['name'=>'Famous Artist','country'=>'USA','genre'=>'Pop','birthYear'=>1985], ["Song1","Song2"], 2010, "Sony Music");
$music2 = new Music("Rock Anthems", 12, 21, "Classic rock songs", ['name'=>'Rock Band','country'=>'UK','genre'=>'Rock','birthYear'=>1978], ["Rock1","Rock2","Rock3"], 2005, "Warner Music");
$music3 = new Music("Pop Collection", 8, 21, "Popular pop songs", ['name'=>'Pop Star','country'=>'Canada','genre'=>'Pop','birthYear'=>1990], ["Pop1","Pop2","Pop3"], 2018, "Universal Music");
$music4 = new Music("Jazz Classics", 11, 21, "Timeless jazz tracks", ['name'=>'Jazz Legend','country'=>'USA','genre'=>'Jazz','birthYear'=>1945], ["Jazz1","Jazz2"], 1975, "Blue Note");
$music5 = new Music("Electronic Beats", 14, 21, "Top electronic songs", ['name'=>'DJ Electro','country'=>'Germany','genre'=>'Electronic','birthYear'=>1988], ["Beat1","Beat2","Beat3","Beat4"], 2020, "Electro Records");

// ----------------- FILMS -----------------
$film1 = new Film("Epic Movie", 15, 21, "Blockbuster movie", "John Director", 2015, 120, "Blu-ray");
$film2 = new Film("Comedy Night", 12, 21, "Funniest moments", "Jane Humor", 2012, 90, "DVD");
$film3 = new Film("Sci-Fi Adventure", 18, 21, "Futuristic adventure", "Max SciFi", 2020, 150, "4K");
$film4 = new Film("Horror Night", 14, 21, "Scary movie", "Horror Master", 2019, 100, "Blu-ray");
$film5 = new Film("Romantic Tales", 13, 21, "Love stories", "Romance King", 2018, 110, "DVD");

// ----------------- GAMES -----------------
$game1 = new Game("Adventure Game", 20, 21, "Exciting adventure", "Adventure", "8GB RAM, GTX 1060", "GameDev Studios", 2018);
$game2 = new Game("Racing Pro", 25, 21, "High-speed racing game", "Racing", "16GB RAM, RTX 2060", "SpeedGames", 2021);
$game3 = new Game("Puzzle Master", 15, 21, "Challenging puzzles", "Puzzle", "4GB RAM, Integrated GPU", "BrainGames", 2019);
$game4 = new Game("Action Hero", 30, 21, "Action-packed shooter", "Action", "16GB RAM, RTX 3070", "ActionCorp", 2022);
$game5 = new Game("Fantasy Quest", 22, 21, "Epic RPG adventure", "RPG", "12GB RAM, GTX 1660", "FantasyStudios", 2020);

// ----------------- PRODUCT LIST -----------------
$productList = new ProductList();
$products = [$music1,$music2,$music3,$music4,$music5,$film1,$film2,$film3,$film4,$film5,$game1,$game2,$game3,$game4,$game5];
foreach($products as $product){
    $productList->addProduct($product);
}

// ----------------- SHOW PRODUCTS -----------------
$productList->showProducts(5);
