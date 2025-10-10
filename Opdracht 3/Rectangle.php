<?php

// made by pascal petri 
// date 10-10-2025

namespace Game;

require_once 'Figuur.php';

class Rectangle extends Figure {
    private int $width;
    private int $height;

    public function __construct(string $color, int $x, int $y, int $width, int $height) {
        parent::__construct($color, $x, $y);
        $this->width = $width;
        $this->height = $height;
    }

    public function render(): string {
        return "<rect x='{$this->getX()}' y='{$this->getY()}' width='{$this->width}' height='{$this->height}' fill='{$this->getColor()}' />";
    }
}

