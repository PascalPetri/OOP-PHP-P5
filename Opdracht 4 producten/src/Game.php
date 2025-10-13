<?php

// name pascal
// date 13-10-2025

require_once "Product.php";

class Game extends Product {
    private string $genre;
    private string $minimumHardware;
    private string $developer;
    private int $releaseYear;

    public function __construct(string $name, float $purchasePrice, float $vat, string $description, string $genre, string $minimumHardware, string $developer, int $releaseYear) {
        parent::__construct($name, $purchasePrice, $vat, $description);
        $this->genre = $genre;
        $this->minimumHardware = $minimumHardware;
        $this->developer = $developer;
        $this->releaseYear = $releaseYear;
        $this->setCategory();
    }

    public function setCategory(): void { $this->category = 'Game'; }

    public function getInfo(): string {
        return "{$this->developer}";
    }

    public function getGenre(): string { return $this->genre; }
    public function getMinimumHardware(): string { return $this->minimumHardware; }
    public function getDeveloper(): string { return $this->developer; }
    public function getReleaseYear(): int { return $this->releaseYear; }
}
