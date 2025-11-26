<?php
// gemaakt door pascal petri
// datum 26-11-2025
require_once 'Dice.php';

// Player class - Vertegenwoordigt een speler met dobbelstenen en scores
class Player implements Serializable {
    private $name;
    private $dice;
    private $throws;
    private $scores;
    private $totalScore;

    // Constructor - Initialiseert een nieuwe speler met naam en dobbelstenen
    public function __construct($name) {
        $this->name = $name;
        $this->dice = array();
        $this->throws = array();
        $this->scores = array();
        $this->totalScore = 0;
        
        for ($i = 0; $i < 5; $i++) {
            $this->dice[] = new Dice();
        }
    }

    // serialize() - Converteert spelerdata naar string voor sessieopslag
    public function serialize() {
        return serialize([
            'name' => $this->name,
            'dice' => $this->dice,
            'throws' => $this->throws,
            'scores' => $this->scores,
            'totalScore' => $this->totalScore
        ]);
    }

    // unserialize() - Herstelt spelerdata van geserialiseerde sessiedata
    public function unserialize($data) {
        $data = unserialize($data);
        $this->name = $data['name'];
        $this->dice = $data['dice'];
        $this->throws = $data['throws'];
        $this->scores = $data['scores'];
        $this->totalScore = $data['totalScore'];
    }

    // getName() - Geeft de naam van de speler terug
    public function getName() {
        return $this->name;
    }

    // throwDice() - Laat de speler alle dobbelstenen gooien en berekent score
    public function throwDice() {
        $currentThrow = array();
        
        foreach ($this->dice as $die) {
            $currentThrow[] = $die->throwDice();
        }
        
        $this->throws[] = $currentThrow;
        
        $score = $this->calculateScore($currentThrow);
        $this->scores[] = $score;
        $this->totalScore += $score;
        
        return $currentThrow;
    }

    // calculateScore() - Bereken de score voor een worp inclusief bonuspunten
    private function calculateScore($throw) {
        $score = array_sum($throw);
        $frequency = array_count_values($throw);
        $maxCount = max($frequency);
        
        if ($maxCount == 5) {
            $score += 50;
        } elseif ($maxCount == 4) {
            $score += 25;
        } elseif ($maxCount == 3 && in_array(2, $frequency)) {
            $score += 20;
        } elseif ($maxCount == 3) {
            $score += 10;
        } elseif (count(array_keys($frequency, 2)) == 2) {
            $score += 15;
        } elseif ($maxCount == 2) {
            $score += 5;
        }

        return $score;
    }

    // getCurrentThrow() - Geeft de laatste worp van de speler terug
    public function getCurrentThrow() {
        if (empty($this->throws)) {
            return array();
        }
        return end($this->throws);
    }

    // getThrowCount() - Geeft het aantal worpen terug dat de speler heeft gedaan
    public function getThrowCount() {
        return count($this->throws);
    }

    // getTotalScore() - Geeft de totale score van de speler terug
    public function getTotalScore() {
        return $this->totalScore;
    }

    // getScores() - Geeft alle scores per worp van de speler terug
    public function getScores() {
        return $this->scores;
    }

    // getThrows() - Geeft alle worpen van de speler terug
    public function getThrows() {
        return $this->throws;
    }

    // getDice() - Geeft alle dobbelstenen van de speler terug
    public function getDice() {
        return $this->dice;
    }

    // applyColorRules() - Past kleurregels toe op dobbelstenen gebaseerd op combinaties
    public function applyColorRules() {
        $currentThrow = $this->getCurrentThrow();
        if (empty($currentThrow)) return;
        
        $frequency = array_count_values($currentThrow);
        
        foreach ($this->dice as $die) {
            $die->setColor("#FFFFFF");
        }
        
        foreach ($frequency as $value => $count) {
            if ($count > 1) {
                $colors = [
                    2 => "#FFD700",
                    3 => "#FF6B6B",
                    4 => "#4ECDC4",
                    5 => "#9B59B6"
                ];
                
                $color = $colors[$count] ?? "#87CEEB";
                
                foreach ($this->dice as $die) {
                    if ($die->getFaceValue() == $value) {
                        $die->setColor($color);
                    }
                }
            }
        }
    }
}
?>