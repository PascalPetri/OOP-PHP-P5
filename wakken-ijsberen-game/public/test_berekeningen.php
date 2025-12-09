<?php
require_once '../classes/Dice.php';
require_once '../classes/CubeDice.php';
require_once '../classes/PentagonDice.php';

echo "<h1>Test Dobbelsteen Berekeningen</h1>";

echo "<h2>Kubus Dobbelsteen (6 zijden)</h2>";
echo "<table border='1'>";
echo "<tr><th>Waarde</th><th>Heeft Wak?</th><th>IJsberen</th><th>Pinguïns</th><th>Tegenover</th></tr>";

for ($i = 1; $i <= 6; $i++) {
    $dice = new CubeDice();
    $dice->setValue($i);
    
    echo "<tr>";
    echo "<td>" . $i . "</td>";
    echo "<td>" . ($dice->hasHole() ? 'Ja' : 'Nee') . "</td>";
    echo "<td>" . $dice->getBears() . "</td>";
    echo "<td>" . $dice->getPenguins() . "</td>";
    echo "<td>" . $dice->getOppositeValue() . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>Pentagon Dobbelsteen (12 zijden)</h2>";
echo "<table border='1'>";
echo "<tr><th>Waarde</th><th>Heeft Wak?</th><th>IJsberen</th><th>Pinguïns</th><th>Tegenover</th></tr>";

for ($i = 1; $i <= 12; $i++) {
    $dice = new PentagonDice();
    $dice->setValue($i);
    
    echo "<tr>";
    echo "<td>" . $i . "</td>";
    echo "<td>" . ($dice->hasHole() ? 'Ja' : 'Nee') . "</td>";
    echo "<td>" . $dice->getBears() . "</td>";
    echo "<td>" . $dice->getPenguins() . "</td>";
    echo "<td>" . $dice->getOppositeValue() . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>Regels Check:</h3>";
echo "<ul>";
echo "<li>Kubus: Wak bij 1, 3, 5 → IJsberen: 0, 2, 4 → Pinguïns: 6, 4, 2</li>";
echo "<li>Pentagon: Wak bij oneven → IJsberen: waarde-1 → Pinguïns: 13-waarde</li>";
echo "<li>Tegenovergestelde: Kubus: 7-waarde, Pentagon: 13-waarde</li>";
echo "</ul>";