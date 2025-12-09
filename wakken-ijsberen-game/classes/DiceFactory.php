<?php
require_once 'CubeDice.php';
require_once 'PentagonDice.php';

class DiceFactory
{
    public static function createDice($type)
    {
        switch ($type) {
            case 'cube':
                return new CubeDice();
            case 'pentagon':
                return new PentagonDice();
            default:
                throw new Exception("Ongeldig dobbelsteen type: $type");
        }
    }

    public static function createMultipleDice($type, $count)
    {
        $dices = [];
        for ($i = 0; $i < $count; $i++) {
            $dices[] = self::createDice($type);
        }
        return $dices;
    }
}