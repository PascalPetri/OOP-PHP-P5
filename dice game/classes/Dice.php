<?php
// gemaakt door pascal petri
// datum 26-11-2025

// Dice class - Vertegenwoordigt een individuele dobbelsteen met waarde en kleur
class Dice implements Serializable {
    // Constante voor het vaste aantal zijden van een standaard dobbelsteen
    const NUMBER_OF_SIDES = 6;
    
    // Private properties voor interne staat opslag - waarde en kleur
    private $faceValue;
    private $color;

    // Constructor - Initialiseert nieuwe dobbelsteen met standaardwaarden
    public function __construct() {
        $this->faceValue = 1;
        $this->color = "#FFFFFF";
    }

    // serialize() - Converteert object naar string voor sessieopslag
    public function serialize() {
        return serialize([
            'faceValue' => $this->faceValue,
            'color' => $this->color
        ]);
    }

    // unserialize() - Herstelt object van geserialiseerde sessiedata
    public function unserialize($data) {
        $data = unserialize($data);
        $this->faceValue = $data['faceValue'];
        $this->color = $data['color'];
    }

    // throwDice() - Simuleert het gooien door random waarde te genereren
    public function throwDice() {
        $this->faceValue = rand(1, self::NUMBER_OF_SIDES);
        return $this->faceValue;
    }

    // getFaceValue() - Geeft de huidige waarde van de dobbelsteen terug
    public function getFaceValue() {
        return $this->faceValue;
    }

    // setColor() - Wijzigt de achtergrondkleur van de dobbelsteen
    public function setColor($color) {
        $this->color = $color;
    }

    // getColor() - Geeft de huidige kleur van de dobbelsteen terug
    public function getColor() {
        return $this->color;
    }

    // getSvg() - Genereert SVG code voor visuele weergave van de dobbelsteen
    public function getSvg() {
        $svg = "<svg width='60' height='60' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg' style='margin:5px; border: 1px solid #000; background-color: {$this->color};'>";
        $svg .= "<rect width='100' height='100' style='fill: {$this->color}; stroke: #000; stroke-width: 2;'/>";
        
        // Array met stip posities voor elke mogelijke dobbelsteen waarde
        $ogenPosities = [
            1 => [[50, 50]],
            2 => [[30, 30], [70, 70]],
            3 => [[30, 30], [50, 50], [70, 70]],
            4 => [[30, 30], [30, 70], [70, 30], [70, 70]],
            5 => [[30, 30], [30, 70], [50, 50], [70, 30], [70, 70]],
            6 => [[30, 30], [30, 50], [30, 70], [70, 30], [70, 50], [70, 70]],
        ];
        
        // Voeg stippen toe gebaseerd op huidige dobbelsteen waarde
        foreach ($ogenPosities[$this->faceValue] as $positie) {
            $svg .= "<circle cx='{$positie[0]}' cy='{$positie[1]}' r='8' fill='black'/>";
        }
        
        $svg .= "</svg>";
        return $svg;
    }
}
?>