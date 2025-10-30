<?php
require_once 'Person.php';
require_once 'GroupClass.php';

class Student extends Person {
    private GroupClass $classroom;
    private bool $paid;

    public function __construct(string $name, GroupClass $classroom, bool $paid = false) {
        parent::__construct($name);
        $this->role = 'Student';
        $this->classroom = $classroom;
        $this->paid = $paid;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getClassName(): string {
        return $this->classroom->getName();
    }

    public function hasPaid(): bool {
        return $this->paid;
    }
}
