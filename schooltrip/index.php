<?php

// Made by Pascal
// Date: October 30, 2025


require_once 'classes/Schooltrip.php';
require_once 'classes/Teacher.php';
require_once 'classes/Student.php';
require_once 'classes/GroupClass.php';

// ===== 1. Maak groepen =====
$groups = [
    new GroupClass("sod2a"),
    new GroupClass("sod2b"),
    new GroupClass("ict1a"),
    new GroupClass("ict1b"),
];

// ===== 2. Maak docenten =====
$teachers = [
    new Teacher("Koningstein", 3500),
    new Teacher("Brugge", 3400),
    new Teacher("Drimmelen", 3600),
    new Teacher("Van Dijk", 3200),
    new Teacher("De Boer", 3300),
    new Teacher("Vermeer", 3100),
    new Teacher("Kuiper", 3450),
];

// ===== 3. Maak studenten (veel meer) =====
$students = [
    new Student("Piet", $groups[0], true),
    new Student("Jan", $groups[0], true),
    new Student("Kees", $groups[0], false),
    new Student("Klaas", $groups[1], true),
    new Student("Mohammed", $groups[1], true),
    new Student("Eefje", $groups[1], false),
    new Student("Martijn", $groups[1], true),
    new Student("Pieter", $groups[0], true),
    new Student("Anna", $groups[2], true),
    new Student("Sophie", $groups[2], false),
    new Student("Tom", $groups[2], true),
    new Student("Lisa", $groups[3], false),
    new Student("Rik", $groups[3], true),
    new Student("Lotte", $groups[3], true),
    new Student("Hassan", $groups[1], false),
    new Student("Daan", $groups[0], true),
    new Student("Emma", $groups[2], false),
    new Student("Sara", $groups[3], true),
    new Student("Niels", $groups[1], true),
    new Student("Yara", $groups[0], false),
];

// ===== 4. Maak meerdere trips =====
$trips = [
    new Schooltrip("Brugge", 5),
    new Schooltrip("Parijs", 5),
    new Schooltrip("Londen", 5),
    new Schooltrip("Berlijn", 5),
];

// ===== 5. Verdeel studenten over trips =====
$tripIndex = 0;
foreach ($students as $s) {
    $trips[$tripIndex]->addStudent($s);
    $tripIndex = ($tripIndex + 1) % count($trips);
}

// ===== 6. Verdeel docenten over trips =====
$teacherIndex = 0;
foreach ($trips as $trip) {
    // elke trip krijgt 1 of 2 docenten
    $trip->addTeacher($teachers[$teacherIndex]);
    $teacherIndex = ($teacherIndex + 1) % count($teachers);
    if ($teacherIndex < count($teachers)) {
        $trip->addTeacher($teachers[$teacherIndex]);
        $teacherIndex = ($teacherIndex + 1) % count($teachers);
    }
    $trip->distributeTeachers();
}

// ===== 7. HTML output =====
echo "<h1>Schooltrip Overzicht</h1>";

foreach ($trips as $trip) {
    echo "<h2>Trip naar {$trip->getName()}</h2>";
    echo "<table border='1' cellpadding='6' cellspacing='0'>";
    echo "<tr><th>Docent</th><th>Student</th><th>Klas</th><th>Betaald</th></tr>";

    foreach ($trip->getLists() as $list) {
        $teacherName = $list->getTeacher() ? $list->getTeacher()->getName() : 'N.v.t.';
        $students = $list->getStudents();
        if (empty($students)) continue;
        $rowspan = count($students);

        foreach ($students as $i => $s) {
            echo "<tr>";
            if ($i === 0) echo "<td rowspan='$rowspan'>$teacherName</td>";
            echo "<td>{$s->getName()}</td>";
            echo "<td>{$s->getClassName()}</td>";
            echo "<td>" . ($s->hasPaid() ? "Ja" : "Nee") . "</td>";
            echo "</tr>";
        }
    }

    echo "</table><br>";
}
