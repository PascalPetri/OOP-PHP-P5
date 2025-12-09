<?php
require_once 'Dice.php';

class CubeDice extends Dice
{
    public function __construct()
    {
        parent::__construct('cube');
        $this->sides = 6;
    }

    public function roll()
    {
        $this->value = rand(1, 6);
        return $this->value;
    }

    public function hasHole()
    {
        return in_array($this->value, [1, 3, 5]);
    }

    public function getBears()
    {
        switch ($this->value) {
            case 1: return 0;
            case 3: return 2;
            case 5: return 4;
            default: return 0;
        }
    }

    public function getPenguins()
    {
        if ($this->hasHole()) {
            return 7 - $this->value;
        }
        return 0;
    }

    public function getOppositeValue()
    {
        return 7 - $this->value;
    }
}