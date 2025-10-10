<?php

// made by pascal petri 
// date 10-10-2025

namespace Game;

require_once 'Figuur.php';

class Triangle extends Figure {
    private array $points;

    public function __construct(string $color, array $points) {
        parent::__construct($color, 0, 0);
        $this->points = $points;
    }

    public function render(): string {
        $pointsStr = implode(' ', array_map(fn($p) => "{$p[0]},{$p[1]}", $this->points));
        return "<polygon points='{$pointsStr}' fill='{$this->getColor()}' />";
    }
}
