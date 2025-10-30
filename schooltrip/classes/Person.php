<?php

// Made by Pascal
// Date: October 30, 2025


abstract class Person {
    protected string $name;
    protected string $role;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    abstract public function getRole(): string;
}
