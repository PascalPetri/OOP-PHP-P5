<?php

// Made by Pascal
// Date: October 30, 2025

require_once 'Student.php';
require_once 'Teacher.php';

class SchooltripList {
    private ?Teacher $teacher = null;
    private array $students = []; // array van Student
    private int $groupSize;

    public function __construct(int $groupSize = 5) {
        $this->groupSize = $groupSize;
    }

    public function addStudent(Student $s): void {
        $this->students[] = $s;
    }

    public function setTeacher(Teacher $t): void {
        $this->teacher = $t;
    }

    public function getTeacher(): ?Teacher {
        return $this->teacher;
    }

    public function getStudents(): array {
        return $this->students;
    }

    public function countPaidStudents(): int {
        $count = 0;
        foreach ($this->students as $s) {
            if ($s->hasPaid()) $count++;
        }
        return $count;
    }

    public function hasRoomForPaid(): bool {
        return $this->countPaidStudents() < $this->groupSize;
    }
}
