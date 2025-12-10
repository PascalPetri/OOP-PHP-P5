<?php

// gemaakt door: pascal
// datum: 10-12-2025

// Laad de basis Dice class in
require_once 'Dice.php';

// CubeDice class - specifiek voor kubusvormige dobbelstenen (6 zijden)
class CubeDice extends Dice
{
    // Constructor: wordt aangeroepen bij aanmaken van nieuwe CubeDice
    public function __construct()
    {
        parent::__construct('cube');  // Roep constructor van parent class (Dice) aan
        $this->sides = 6;  // Zet aantal zijden op 6 voor kubus dobbelsteen
    }

    // Rol de dobbelsteen
    public function roll()
    {
        $this->value = rand(1, 6);  // Genereer willekeurig getal tussen 1 en 6
        return $this->value;  // Geef gegooide waarde terug
    }

    // Controleer of deze dobbelsteen een wak (hole) heeft
    public function hasHole()
    {
        return in_array($this->value, [1, 3, 5]);  // True als waarde 1, 3 of 5 is (oneven)
    }

    // Bereken aantal ijsberen voor deze dobbelsteen
    public function getBears()
    {
        switch ($this->value) {
            case 1: return 0;  // Waarde 1 geeft 0 ijsberen
            case 3: return 2;  // Waarde 3 geeft 2 ijsberen
            case 5: return 4;  // Waarde 5 geeft 4 ijsberen
            default: return 0; // Andere waarden (even) geven 0 ijsberen
        }
    }

    // Bereken aantal pinguïns voor deze dobbelsteen
    public function getPenguins()
    {
        if ($this->hasHole()) {  // Alleen berekenen als er een wak is
            return 7 - $this->value;  // Formule: 7 min dobbelsteen waarde
        }
        return 0;  // Geen wak = geen pinguïns
    }

    // Bereken tegenovergestelde waarde op dobbelsteen
    public function getOppositeValue()
    {
        return 7 - $this->value;  // Bij kubus: som tegenoverliggende zijden is altijd 7
    }
}