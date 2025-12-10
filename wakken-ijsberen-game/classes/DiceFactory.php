<?php

// gemaakt door: pascal
// datum: 10-12-2025

// Laad de benodigde dobbelsteen classes in
require_once 'CubeDice.php';
require_once 'PentagonDice.php';

// Factory pattern class voor het aanmaken van dobbelstenen
// Centraliseert het aanmaakproces van objecten
class DiceFactory
{
    // Statische methode: kan worden aangeroepen zonder de class te instantiëren
    
    // Maak één dobbelsteen aan op basis van type
    public static function createDice($type)
    {
        switch ($type) {
            case 'cube':  // Voor kubus dobbelstenen
                return new CubeDice();  // Maak nieuwe CubeDice aan en retourneer
                
            case 'pentagon':  // Voor pentagon dobbelstenen
                return new PentagonDice();  // Maak nieuwe PentagonDice aan en retourneer
                
            default:  // Als type niet herkend wordt
                // Gooi een uitzondering met foutmelding
                throw new Exception("Ongeldig dobbelsteen type: $type");
        }
    }

    // Maak meerdere dobbelstenen aan van hetzelfde type
    public static function createMultipleDice($type, $count)
    {
        $dices = [];  // Maak lege array voor dobbelstenen
        
        // Loop $count aantal keer
        for ($i = 0; $i < $count; $i++) {
            // Maak een dobbelsteen aan en voeg toe aan array
            $dices[] = self::createDice($type);
        }
        
        return $dices;  // Retourneer array met alle dobbelstenen
    }
}