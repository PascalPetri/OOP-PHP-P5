<?php

// gemaakt door: pascal
// datum: 10-12-2025

// Abstracte basis class voor alle dobbelstenen
// Kan niet direct worden geïnstantieerd, alleen via child classes
abstract class Dice
{
    // Protected properties - alleen toegankelijk binnen deze class en child classes
    protected $sides;  // Aantal zijden van de dobbelsteen (bijv. 6 voor kubus, 12 voor pentagon)
    protected $value;  // Huidige gegooide waarde van de dobbelsteen
    protected $type;   // Type dobbelsteen (bijv. 'cube', 'pentagon')

    // Constructor - wordt aangeroepen bij aanmaken van een nieuwe dobbelsteen
    public function __construct($type)
    {
        $this->type = $type;  // Sla het type dobbelsteen op
    }

    // Abstracte methodes - MOETEN worden geïmplementeerd door child classes
    
    abstract public function roll();  // Gooi de dobbelsteen
    abstract public function hasHole();  // Controleer of er een wak is
    abstract public function getBears();  // Bereken aantal ijsberen
    abstract public function getPenguins();  // Bereken aantal pinguïns
    abstract public function getOppositeValue();  // Bereken tegenoverliggende waarde
    
    // Concrete methodes - hebben al een implementatie
    
    // Zet de waarde van de dobbelsteen handmatig (bijv. voor testen)
    public function setValue($value)
    {
        $this->value = $value;
    }

    // Haal de huidige waarde van de dobbelsteen op
    public function getValue()
    {
        return $this->value;
    }

    // Haal het type dobbelsteen op
    public function getType()
    {
        return $this->type;
    }

    // Haal het aantal zijden van de dobbelsteen op
    public function getSides()
    {
        return $this->sides;
    }
}