<?php

// made by pascal petri 
// date 10-10-2025

namespace Game;

abstract class Figure {
    private string $color;
    private int $x;
    private int $y;

    public function __construct(string $color, int $x, int $y) {
        $this->setColor($color);
        $this->setX($x);
        $this->setY($y);
    }

    // Getters
    public function getColor(): string {
        return $this->color;
    }

    public function getX(): int {
        return $this->x;
    }

    public function getY(): int {
        return $this->y;
    }

    // Setter with NL → EN and EN → EN color mapping for SVG
    public function setColor(string $color): void {
        $color = strtolower($color);
        
        $allowedColors = [
            // Dutch → English
            'rood'   => 'red',
            'groen'  => 'green',
            'blauw'  => 'blue',
            'geel'   => 'yellow',
            'oranje' => 'orange',
            'paars'  => 'purple',
            // English → English
            'red'    => 'red',
            'green'  => 'green',
            'blue'   => 'blue',
            'yellow' => 'yellow',
            'orange' => 'orange',
            'purple' => 'purple'
        ];

        if(array_key_exists($color, $allowedColors)) {
            $this->color = $allowedColors[$color];
        } else {
            throw new \Exception("Color not allowed: $color");
        }
    }

    public function setX(int $x): void {
        $this->x = $x;
    }

    public function setY(int $y): void {
        $this->y = $y;
    }

    // Abstract method for SVG rendering
    abstract public function render(): string;
}
