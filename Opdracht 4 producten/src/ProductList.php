<?php

// name pascal
// date 13-10-2025

require_once "Music.php";
require_once "Film.php";
require_once "Game.php";

class ProductList {
    private array $products = [];

    public function addProduct(Product $product): void {
        $this->products[] = $product;
    }

    public function showProducts(float $profit): void {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr>
                <th>Name</th>
                <th>Category</th>
                <th>Sale Price</th>
                <th>Description</th>
                <th>Artist / Director / Developer</th>
                <th>Additional Info</th>
              </tr>";

        foreach ($this->products as $product) {
            echo "<tr>";
            echo "<td>{$product->getName()}</td>";
            echo "<td>{$product->getCategory()}</td>";
            echo "<td>€" . number_format($product->getSalePrice($profit), 2) . "</td>";
            echo "<td>{$product->getDescription()}</td>";

            // Specifieke info per type
            if ($product instanceof Music) {
                echo "<td>{$product->getArtistName()}</td>";
                echo "<td>Birth Year: {$product->getArtistBirthYear()}, Country: {$product->getArtistCountry()}, Genre: {$product->getArtistGenre()}, Release: {$product->getReleaseYear()}, Label: {$product->getLabel()}, Tracks: {$product->getTracksString()}</td>";
            } elseif ($product instanceof Film) {
                echo "<td>{$product->getDirector()}</td>";
                echo "<td>Release: {$product->getReleaseYear()}, Duration: {$product->getDuration()} min, Quality: {$product->getQuality()}</td>";
            } elseif ($product instanceof Game) {
                echo "<td>{$product->getDeveloper()}</td>";
                echo "<td>Genre: {$product->getGenre()}, Min Hardware: {$product->getMinimumHardware()}, Release: {$product->getReleaseYear()}</td>";
            }

            echo "</tr>";
        }

        echo "</table>";
    }
}
