<?php
//gemaakt door pascal petri
//gemaakt op 13-9-2025

// class huizen
class Huis {
    // Properties (eigenschappen) standaard private (encapsulation)
    private int $aantalVerdiepingen;
    private int $aantalKamers;
    private float $breedte;
    private float $hoogte;
    private float $diepte;
    private float $prijsPerM3 = 1500;

    // Constructor
    public function __construct(int $aantalVerdiepingen, int $aantalKamers, float $breedte, float $hoogte, float $diepte) {
        $this->aantalVerdiepingen = $aantalVerdiepingen;
        $this->aantalKamers = $aantalKamers;
        $this->breedte = $breedte;
        $this->hoogte = $hoogte;
        $this->diepte = $diepte;
    }

    // Getter & Setter voor aantalVerdiepingen
    public function getAantalVerdiepingen(): int {
        return $this->aantalVerdiepingen;
    }

    public function setAantalVerdiepingen(int $aantalVerdiepingen): void {
        $this->aantalVerdiepingen = $aantalVerdiepingen;
    }

    // Volume berekenen lengte x breed x hoogte voor m³
    public function berekenVolume(): float {
        return $this->breedte * $this->hoogte * $this->diepte;
    }

    // Prijs berekenen de prijs is 1500 per m3
    public function berekenPrijs(): float {
        return $this->berekenVolume() * $this->prijsPerM3;
    }

    // Details tonen van de huisen.
    public function toonDetails(): void {
        echo "Huis details:<br>";
        echo "Aantal verdiepingen: " . $this->aantalVerdiepingen . "<br>";
        echo "Aantal kamers: " . $this->aantalKamers . "<br>";
        echo "Breedte: " . $this->breedte . " m<br>";
        echo "Hoogte: " . $this->hoogte . " m<br>";
        echo "Diepte: " . $this->diepte . " m<br>";
        echo "Volume: " . $this->berekenVolume() . " m³<br>";
        echo "Prijs: €" . number_format($this->berekenPrijs(), 2, ',', '.') . "<br><br>";
    }
}

//  Uitvoering: drie huizen maken.
$huis1 = new Huis(2, 5, 8.0, 6.0, 10.0);
$huis2 = new Huis(3, 7, 10.0, 8.0, 12.0);
$huis3 = new Huis(1, 3, 6.0, 4.0, 9.0);


// Details printen van de huizen.
$huis1->toonDetails();
$huis2->toonDetails();
$huis3->toonDetails();
