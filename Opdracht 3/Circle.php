<?php

// made by pascal petri 
// date 10-10-2025

namespace Game;

require_once 'Figuur.php';

class Circle extends Figure {
    private int $radius;

    public function __construct(string $color, int $x, int $y, int $radius) {
        parent::__construct($color, $x, $y);
        $this->radius = $radius;
    }

    public function render(): string {
        return "<circle cx='{$this->getX()}' cy='{$this->getY()}' r='{$this->radius}' fill='{$this->getColor()}' />";
    }
}
