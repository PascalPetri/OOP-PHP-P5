<?php

// gemaakt door: pascal
// datum: 10-12-2025

// Laad de basis Dice class in
require_once 'Dice.php';

// PentagonDice class - specifiek voor pentagon-vormige dobbelstenen (12 zijden)
class PentagonDice extends Dice
{
    // Constructor: wordt aangeroepen bij aanmaken van nieuwe PentagonDice
    public function __construct()
    {
        parent::__construct('pentagon');  // Roep constructor van parent class (Dice) aan
        $this->sides = 12;  // Zet aantal zijden op 12 voor pentagon dobbelsteen
    }

    // Rol de dobbelsteen
    public function roll()
    {
        $this->value = rand(1, 12);  // Genereer willekeurig getal tussen 1 en 12
        return $this->value;  // Geef gegooide waarde terug
    }

    // Controleer of deze dobbelsteen een wak (hole) heeft
    public function hasHole()
    {
        return $this->value % 2 != 0;  // True als waarde oneven is (1, 3, 5, 7, 9, 11)
    }

    // Bereken aantal ijsberen voor deze dobbelsteen
    public function getBears()
    {
        if ($this->hasHole()) {  // Alleen berekenen als er een wak is
            return $this->value - 1;  // Formule: dobbelsteen waarde min 1
        }
        return 0;  // Geen wak = geen ijsberen
    }

    // Bereken aantal pinguïns voor deze dobbelsteen
    public function getPenguins()
    {
        if ($this->hasHole()) {  // Alleen berekenen als er een wak is
            return 13 - $this->value;  // Formule: 13 min dobbelsteen waarde
        }
        return 0;  // Geen wak = geen pinguïns
    }

    // Bereken tegenovergestelde waarde op dobbelsteen
    public function getOppositeValue()
    {
        return 13 - $this->value;  // Bij pentagon: som tegenoverliggende zijden is altijd 13
    }
}