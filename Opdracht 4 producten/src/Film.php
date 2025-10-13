<?php

// name pascal
// date 13-10-2025

require_once "Product.php";

class Film extends Product {
    private string $director;
    private int $releaseYear;
    private int $duration;
    private string $quality;

    public function __construct(string $name, float $purchasePrice, float $vat, string $description, string $director, int $releaseYear, int $duration, string $quality) {
        parent::__construct($name, $purchasePrice, $vat, $description);
        $this->director = $director;
        $this->releaseYear = $releaseYear;
        $this->duration = $duration;
        $this->quality = $quality;
        $this->setCategory();
    }

    public function setCategory(): void { $this->category = 'Film'; }

    public function getInfo(): string {
        return "{$this->director}";
    }

    public function getDirector(): string { return $this->director; }
    public function getReleaseYear(): int { return $this->releaseYear; }
    public function getDuration(): int { return $this->duration; }
    public function getQuality(): string { return $this->quality; }
}
