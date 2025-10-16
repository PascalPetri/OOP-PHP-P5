<?php
// Created by Pascal | Date: 16-10-2025
// Nurse class – fixed salary + bonus per appointment

require_once 'Staff.php';

class Nurse extends Staff {
    private float $baseSalary;
    private float $bonusPerAppointment;
    private int $appointments = 0;

    public function __construct(string $name, int $age, float $baseSalary, float $bonusPerAppointment) {
        parent::__construct($name, $age);
        $this->baseSalary = $baseSalary;
        $this->bonusPerAppointment = $bonusPerAppointment;
    }

    public function addAppointment(): void {
        $this->appointments++;
    }

    public function calculateSalary(): float {
        return $this->baseSalary + ($this->appointments * $this->bonusPerAppointment);
    }

    public function getRole(): string {
        return "Nurse";
    }

    // NEW: getter for bonus per appointment
    public function getBonusPerAppointment(): float {
        return $this->bonusPerAppointment;
    }

    // Optional: getter for total appointments
    public function getAppointments(): int {
        return $this->appointments;
    }
}
