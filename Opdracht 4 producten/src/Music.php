<?php

// name pascal
// date 13-10-2025

require_once "Product.php";

class Music extends Product {
    private array $artist; // ['name','country','genre','birthYear']
    private array $tracks;
    private int $releaseYear;
    private string $label;

    public function __construct(string $name, float $purchasePrice, float $vat, string $description, array $artist, array $tracks, int $releaseYear, string $label) {
        parent::__construct($name, $purchasePrice, $vat, $description);
        $this->artist = $artist;
        $this->tracks = $tracks;
        $this->releaseYear = $releaseYear;
        $this->label = $label;
        $this->setCategory();
    }

    public function setCategory(): void { $this->category = 'Music'; }

    public function getInfo(): string {
        return implode(', ', $this->tracks);
    }

    // Extra getters voor tabel
    public function getArtistName(): string { return $this->artist['name']; }
    public function getArtistBirthYear(): int { return $this->artist['birthYear']; }
    public function getArtistCountry(): string { return $this->artist['country']; }
    public function getArtistGenre(): string { return $this->artist['genre']; }
    public function getReleaseYear(): int { return $this->releaseYear; }
    public function getLabel(): string { return $this->label; }
    public function getTracksString(): string { return implode(', ', $this->tracks); }
}
