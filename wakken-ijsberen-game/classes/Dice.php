<?php
abstract class Dice
{
    protected $sides;
    protected $value;
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    abstract public function roll();
    abstract public function hasHole();
    abstract public function getBears();
    abstract public function getPenguins();
    abstract public function getOppositeValue();
    
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSides()
    {
        return $this->sides;
    }
}