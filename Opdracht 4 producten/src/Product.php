<?php

// name pascal
// date 13-10-2025

abstract class Product {
    private string $name;
    private float $purchasePrice;
    private float $vat;
    private string $description;
    protected string $category;

    public function __construct(string $name, float $purchasePrice, float $vat, string $description) {
        $this->name = $name;
        $this->purchasePrice = $purchasePrice;
        $this->vat = $vat;
        $this->description = $description;
    }

    public function getName(): string { return $this->name; }
    public function getPurchasePrice(): float { return $this->purchasePrice; }
    public function getVat(): float { return $this->vat; }
    public function getDescription(): string { return $this->description; }
    public function getCategory(): string { return $this->category; }

    public function getSalePrice(float $profit): float {
        return $this->purchasePrice + $profit + ($this->purchasePrice + $profit) * $this->vat / 100;
    }

    abstract public function getInfo(): string;
    abstract public function setCategory(): void;
}
