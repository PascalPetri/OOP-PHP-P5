<?php

// made by pascal petri 
// date 10-10-2025

namespace Game;

require_once 'Figuur.php';

class Square extends Figure {
    private int $size;

    public function __construct(string $color, int $x, int $y, int $size) {
        parent::__construct($color, $x, $y);
        $this->size = $size;
    }

    public function render(): string {
        return "<rect x='{$this->getX()}' y='{$this->getY()}' width='{$this->size}' height='{$this->size}' fill='{$this->getColor()}' />";
    }
}

