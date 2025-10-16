<?php

// Created by Pascal | Date: 16-10-2025
// Abstract staff class for doctors and nurses.


require_once 'Person.php';

abstract class Staff extends Person {
    protected float $salary;

    public function __construct(string $name, int $age) {
        parent::__construct($name, $age);
    }

    abstract public function calculateSalary(): float;

    public function getSalary(): float {
        return $this->salary;
    }
}
