<?php

// Made by Pascal
// Date: October 30, 2025


require_once 'SchooltripList.php';

class Schooltrip {
    private string $name;
    private array $lists = [];
    private array $teachers = [];
    private int $groupSize;

    public function __construct(string $name, int $groupSize = 5) {
        $this->name = $name;
        $this->groupSize = $groupSize;
    }

    public function addStudent(Student $s): void {
        if (empty($this->lists)) {
            $this->lists[] = new SchooltripList($this->groupSize);
        }

        foreach ($this->lists as $list) {
            if ($s->hasPaid() && $list->hasRoomForPaid()) {
                $list->addStudent($s);
                return;
            }
        }

        $new = new SchooltripList($this->groupSize);
        $new->addStudent($s);
        $this->lists[] = $new;
    }

    public function addTeacher(Teacher $t): void {
        $this->teachers[] = $t;
    }

    public function distributeTeachers(): void {
        $index = 0;
        foreach ($this->lists as $list) {
            if (isset($this->teachers[$index])) {
                $list->setTeacher($this->teachers[$index]);
            }
            $index++;
            if ($index >= count($this->teachers)) break;
        }
    }

    public function getLists(): array {
        return $this->lists;
    }

    // ✅ FIX: Voeg deze toe
    public function getName(): string {
        return $this->name;
    }
}
