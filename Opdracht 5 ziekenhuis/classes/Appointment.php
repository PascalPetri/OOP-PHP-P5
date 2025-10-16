<?php
// Created by Pascal | Date: 16-10-2025
// Appointment between a patient and doctor (optionally nurse).

require_once 'Patient.php';
require_once 'Doctor.php';
require_once 'Nurse.php';

class Appointment {
    private Patient $patient;
    private Doctor $doctor;
    private ?Nurse $nurse;
    private DateTime $startTime;
    private DateTime $endTime;

    public function __construct(Patient $patient, Doctor $doctor, DateTime $startTime, DateTime $endTime, ?Nurse $nurse = null) {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->nurse = $nurse;
        $this->startTime = $startTime;
        $this->endTime = $endTime;

        // register appointment to staff once
        $this->doctor->addAppointment();
        if ($this->nurse !== null) $this->nurse->addAppointment();
    }

    // Return duration in hours (float)
    public function getDurationInHours(): float {
        $interval = $this->startTime->diff($this->endTime);
        return $interval->h + ($interval->i / 60);
    }

    // NEW: return duration in minutes (int)
    public function getDurationInMinutes(): int {
        $interval = $this->startTime->diff($this->endTime);
        return ($interval->h * 60) + $interval->i;
    }

    // NEW: getters for start and end DateTime objects
    public function getStart(): DateTime {
        return $this->startTime;
    }

    public function getEnd(): DateTime {
        return $this->endTime;
    }

    public function getPatient(): Patient {
        return $this->patient;
    }

    public function getDoctor(): Doctor {
        return $this->doctor;
    }

    public function getNurse(): ?Nurse {
        return $this->nurse;
    }

    public function getAppointmentInfo(): string {
        $nurseName = $this->nurse ? $this->nurse->getName() : "No nurse";
        return "Appointment: " . $this->patient->getName() . " with Dr. " . $this->doctor->getName() .
               " and " . $nurseName . " | Duration: " . $this->getDurationInHours() . " hour(s)";
    }
}
