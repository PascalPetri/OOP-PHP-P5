<?php
// Created by Pascal | Date: 16-10-2025
// Represents a doctor with an hourly rate and appointment tracking

require_once 'Person.php';

class Doctor extends Person {
    private float $hourlyRate;
    private int $appointments = 0;

    public function __construct(string $name, int $age, float $hourlyRate) {
        parent::__construct($name, $age);
        $this->hourlyRate = $hourlyRate;
    }

    // Adds an appointment to the doctor's schedule
    public function addAppointment(): void {
        $this->appointments++;
    }

    // Calculates total salary based on number of appointments
    public function calculateSalary(): float {
        return $this->appointments * $this->hourlyRate;
    }

    // Getter for hourly rate
    public function getHourlyRate(): float {
        return $this->hourlyRate;
    }

    // Getter for total appointments
    public function getAppointments(): int {
        return $this->appointments;
    }

    // IMPLEMENTATION OF ABSTRACT METHOD FROM PERSON
    public function getRole(): string {
        return "Doctor";
    }
}
