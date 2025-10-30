<?php
require_once 'Person.php';

class Teacher extends Person {
    private float $salary;

    public function __construct(string $name, float $salary = 0.0) {
        parent::__construct($name);
        $this->role = 'Teacher';
        $this->salary = $salary;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getSalary(): float {
        return $this->salary;
    }
}
