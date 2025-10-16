<?php

// Created by Pascal | Date: 16-10-2025
// Class for patients who pay per treatment.

require_once 'Person.php';

class Patient extends Person {
    private float $paymentPerTreatment;

    public function __construct(string $name, int $age, float $paymentPerTreatment) {
        parent::__construct($name, $age);
        $this->paymentPerTreatment = $paymentPerTreatment;
    }

    public function getPaymentPerTreatment(): float {
        return $this->paymentPerTreatment;
    }

    public function setPaymentPerTreatment(float $amount): void {
        $this->paymentPerTreatment = $amount;
    }

    public function getRole(): string {
        return "Patient";
    }
}
