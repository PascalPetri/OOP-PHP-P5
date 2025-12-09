<?php
require_once 'Dice.php';

class PentagonDice extends Dice
{
    public function __construct()
    {
        parent::__construct('pentagon');
        $this->sides = 12;
    }

    public function roll()
    {
        $this->value = rand(1, 12);
        return $this->value;
    }

    public function hasHole()
    {
        return $this->value % 2 != 0;
    }

    public function getBears()
    {
        if ($this->hasHole()) {
            return $this->value - 1;
        }
        return 0;
    }

    public function getPenguins()
    {
        if ($this->hasHole()) {
            return 13 - $this->value;
        }
        return 0;
    }

    public function getOppositeValue()
    {
        return 13 - $this->value;
    }
}