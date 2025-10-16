<?php
// Created by Pascal | Date: 16-10-2025
// This file creates patients, doctors, nurses, and appointments
// Displays all appointments and calculates salaries.

require_once './classes/Appointment.php';

// === Create objects ===
$patient1 = new Patient("Pascal Petri", 20, 50);
$patient2 = new Patient("Emma de Vries", 28, 60);
$patient3 = new Patient("Liam Bakker", 35, 55);
$patient4 = new Patient("Sophie Visser", 42, 70);
$patient5 = new Patient("Noah Willems", 30, 65);
$patient6 = new Patient("Mila Dekker", 25, 50);

$doctor1 = new Doctor("Dr. Jansen", 45, 75);
$doctor2 = new Doctor("Dr. Vermeer", 50, 85);

$nurse1 = new Nurse("Anne", 32, 2500, 25);
$nurse2 = new Nurse("Tom", 40, 2600, 20);

// === Create appointments (now 12 total) ===
$appointment1  = new Appointment($patient1, $doctor1, new DateTime("2025-10-16 10:00"), new DateTime("2025-10-16 11:30"), $nurse1);
$appointment2  = new Appointment($patient1, $doctor1, new DateTime("2025-10-17 09:00"), new DateTime("2025-10-17 09:45"));
$appointment3  = new Appointment($patient2, $doctor1, new DateTime("2025-10-18 13:00"), new DateTime("2025-10-18 14:15"), $nurse2);
$appointment4  = new Appointment($patient3, $doctor2, new DateTime("2025-10-19 15:00"), new DateTime("2025-10-19 16:30"), $nurse1);
$appointment5  = new Appointment($patient4, $doctor2, new DateTime("2025-10-20 11:00"), new DateTime("2025-10-20 12:00"));
$appointment6  = new Appointment($patient3, $doctor1, new DateTime("2025-10-21 08:30"), new DateTime("2025-10-21 09:15"), $nurse2);
$appointment7  = new Appointment($patient2, $doctor2, new DateTime("2025-10-22 14:00"), new DateTime("2025-10-22 15:00"), $nurse1);
$appointment8  = new Appointment($patient5, $doctor1, new DateTime("2025-10-23 09:30"), new DateTime("2025-10-23 10:30"), $nurse2);
$appointment9  = new Appointment($patient6, $doctor2, new DateTime("2025-10-24 10:00"), new DateTime("2025-10-24 11:00"));
$appointment10 = new Appointment($patient5, $doctor2, new DateTime("2025-10-25 15:00"), new DateTime("2025-10-25 16:30"), $nurse1);
$appointment11 = new Appointment($patient6, $doctor1, new DateTime("2025-10-26 08:45"), new DateTime("2025-10-26 09:30"));
$appointment12 = new Appointment($patient4, $doctor2, new DateTime("2025-10-27 13:00"), new DateTime("2025-10-27 14:30"), $nurse2);

// Store all appointments
$appointments = [
    $appointment1, $appointment2, $appointment3, $appointment4, $appointment5, $appointment6,
    $appointment7, $appointment8, $appointment9, $appointment10, $appointment11, $appointment12
];

// === Track appointments for salary calculations ===
foreach ($appointments as $a) {
    $a->getDoctor()->addAppointment();
    if ($a->getNurse() !== null) {
        $a->getNurse()->addAppointment();
    }
}

// === Calculate salaries ===
$doctor1Salary = $doctor1->calculateSalary();
$doctor2Salary = $doctor2->calculateSalary();
$nurse1Salary  = $nurse1->calculateSalary();
$nurse2Salary  = $nurse2->calculateSalary();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Overview</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f8f9fa; }
        h1 { text-align: center; }
        table { border-collapse: collapse; width: 100%; background: white; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        .summary { background: #e9ecef; padding: 15px; border-radius: 10px; }
    </style>
</head>
<body>

<h1>Appointment Overview</h1>

<table>
    <tr>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Nurse</th>
        <th>Date</th>
        <th>Start</th>
        <th>End</th>
        <th>Duration (min)</th>
        <th>Doctor Pay (€)</th>
        <th>Nurse Bonus (€)</th>
    </tr>

    <?php foreach ($appointments as $a): ?>
        <?php
        $duration = $a->getDurationInMinutes();
        $doctorPay = $a->getDoctor()->getHourlyRate() * ($duration / 60);
        $nurseBonus = $a->getNurse() ? $a->getNurse()->getBonusPerAppointment() : 0;
        ?>
        <tr>
            <td><?= $a->getPatient()->getName(); ?></td>
            <td><?= $a->getDoctor()->getName(); ?></td>
            <td><?= $a->getNurse() ? $a->getNurse()->getName() : '-'; ?></td>
            <td><?= $a->getStart()->format('Y-m-d'); ?></td>
            <td><?= $a->getStart()->format('H:i'); ?></td>
            <td><?= $a->getEnd()->format('H:i'); ?></td>
            <td><?= $duration; ?></td>
            <td>€<?= number_format($doctorPay, 2); ?></td>
            <td>€<?= number_format($nurseBonus, 2); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<div class="summary">
    <h2>Salary Summary</h2>
    <p><strong><?= $doctor1->getName(); ?>:</strong> €<?= number_format($doctor1Salary, 2); ?> (<?= $doctor1->getRole(); ?>)</p>
    <p><strong><?= $doctor2->getName(); ?>:</strong> €<?= number_format($doctor2Salary, 2); ?> (<?= $doctor2->getRole(); ?>)</p>
    <p><strong><?= $nurse1->getName(); ?>:</strong> €<?= number_format($nurse1Salary, 2); ?> (<?= $nurse1->getRole(); ?>)</p>
    <p><strong><?= $nurse2->getName(); ?>:</strong> €<?= number_format($nurse2Salary, 2); ?> (<?= $nurse2->getRole(); ?>)</p>
</div>

</body>
</html>
