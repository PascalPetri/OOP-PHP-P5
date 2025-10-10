
<?php
// made by pascal petri 
// date 10-10-2025

require_once 'Square.php';
require_once 'Circle.php';
require_once 'Rectangle.php';
require_once 'Triangle.php';

use Game\Square;
use Game\Circle;
use Game\Rectangle;
use Game\Triangle;

$colors = ['red','green','blue','yellow','orange','purple'];
$shapes = [];
$title = 'Three in a Row - All Colors';

$startX = 50; // previously 10, shifts everything to the right
$startY = 10;
$gap = 100; // space between shapes

foreach($colors as $index => $color) {
    $xPos = $startX + $index * $gap;

    $shapes[] = new Square($color, $xPos, $startY, 50);
    $shapes[] = new Circle($color, $xPos, $startY + 100, 25); // circle slightly below
    $shapes[] = new Rectangle($color, $xPos, $startY + 140, 60, 30);
    $shapes[] = new Triangle($color, [
        [$xPos, $startY + 190],
        [$xPos + 30, $startY + 240],
        [$xPos - 30, $startY + 240]
    ]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
</head>
<body>
<svg width="650" height="300" style="border:1px solid black;">
    <?php 
        foreach($shapes as $shape):
            echo $shape->render();
        endforeach; 
    ?>
</svg>
</body>
</html>
