<?php
// Simpele dobbelsteen spel - GEEN SESSIES, GEEN COMPLEXE OOP
class SimpleDiceGame {
    private $players;
    private $currentPlayer;
    private $throws;
    private $scores;
    private $currentThrow;
    private $maxThrows;
    private $gameStarted;
    
    public function __construct($numberOfPlayers = 1) {
        $this->players = $numberOfPlayers;
        $this->currentPlayer = 0;
        $this->throws = array_fill(0, $numberOfPlayers, array());
        $this->scores = array_fill(0, $numberOfPlayers, array());
        $this->currentThrow = 0;
        $this->maxThrows = 3;
        $this->gameStarted = false;
    }
    
    public function startGame() {
        $this->gameStarted = true;
    }
    
    public function throwDice() {
        if (!$this->gameStarted || $this->isGameFinished()) {
            return false;
        }
        
        // Gooi 5 dobbelstenen
        $throw = [];
        for ($i = 0; $i < 5; $i++) {
            $throw[] = rand(1, 6);
        }
        
        $this->throws[$this->currentPlayer][] = $throw;
        
        // Bereken score
        $score = $this->calculateScore($throw);
        $this->scores[$this->currentPlayer][] = $score;
        
        $this->currentThrow++;
        
        // Volgende speler na 3 worpen
        if ($this->currentThrow >= $this->maxThrows) {
            $this->currentPlayer = ($this->currentPlayer + 1) % $this->players;
            $this->currentThrow = 0;
        }
        
        return true;
    }
    
    private function calculateScore($throw) {
        $score = array_sum($throw);
        $frequency = array_count_values($throw);
        $maxCount = max($frequency);
        
        // Bonus punten
        if ($maxCount == 5) {
            $score += 50; // Yahtzee
        } elseif ($maxCount == 4) {
            $score += 25; // Four of a kind
        } elseif ($maxCount == 3 && in_array(2, $frequency)) {
            $score += 20; // Full house
        } elseif ($maxCount == 3) {
            $score += 10; // Three of a kind
        } elseif (count(array_keys($frequency, 2)) == 2) {
            $score += 15; // Two pairs
        } elseif ($maxCount == 2) {
            $score += 5; // One pair
        }
        
        return $score;
    }
    
    public function getCurrentPlayer() {
        return $this->currentPlayer;
    }
    
    public function getCurrentThrow() {
        return $this->currentThrow;
    }
    
    public function getMaxThrows() {
        return $this->maxThrows;
    }
    
    public function getPlayers() {
        return $this->players;
    }
    
    public function getThrows($player) {
        return isset($this->throws[$player]) ? $this->throws[$player] : [];
    }
    
    public function getScores($player) {
        return isset($this->scores[$player]) ? $this->scores[$player] : [];
    }
    
    public function getTotalScore($player) {
        return isset($this->scores[$player]) ? array_sum($this->scores[$player]) : 0;
    }
    
    public function isGameStarted() {
        return $this->gameStarted;
    }
    
    public function isGameFinished() {
        // Spel is afgelopen als alle spelers 3 worpen hebben gedaan
        foreach ($this->scores as $playerScores) {
            if (count($playerScores) < $this->maxThrows) {
                return false;
            }
        }
        return $this->players > 0;
    }
    
    public function getWinner() {
        if (!$this->isGameFinished()) {
            return null;
        }
        
        $winner = 0;
        $maxScore = $this->getTotalScore(0);
        
        for ($i = 1; $i < $this->players; $i++) {
            $score = $this->getTotalScore($i);
            if ($score > $maxScore) {
                $maxScore = $score;
                $winner = $i;
            }
        }
        
        return $winner;
    }
    
    // Serialize game state voor form
    public function serialize() {
        return base64_encode(serialize([
            'players' => $this->players,
            'currentPlayer' => $this->currentPlayer,
            'throws' => $this->throws,
            'scores' => $this->scores,
            'currentThrow' => $this->currentThrow,
            'gameStarted' => $this->gameStarted
        ]));
    }
    
    // Unserialize game state van form
    public static function unserialize($data) {
        $data = unserialize(base64_decode($data));
        $game = new self($data['players']);
        $game->currentPlayer = $data['currentPlayer'];
        $game->throws = $data['throws'];
        $game->scores = $data['scores'];
        $game->currentThrow = $data['currentThrow'];
        $game->gameStarted = $data['gameStarted'];
        return $game;
    }
}

// Functie om dobbelsteen SVG te genereren
function generateDiceSVG($value, $color = "#FFFFFF") {
    $svg = "<svg width='60' height='60' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg' style='margin:5px; border: 1px solid #000; background-color: $color;'>";
    $svg .= "<rect width='100' height='100' style='fill: $color; stroke: #000; stroke-width: 2;'/>";
    
    $positions = [
        1 => [[50, 50]],
        2 => [[30, 30], [70, 70]],
        3 => [[30, 30], [50, 50], [70, 70]],
        4 => [[30, 30], [30, 70], [70, 30], [70, 70]],
        5 => [[30, 30], [30, 70], [50, 50], [70, 30], [70, 70]],
        6 => [[30, 30], [30, 50], [30, 70], [70, 30], [70, 50], [70, 70]],
    ];
    
    foreach ($positions[$value] as $pos) {
        $svg .= "<circle cx='{$pos[0]}' cy='{$pos[1]}' r='8' fill='black'/>";
    }
    
    $svg .= "</svg>";
    return $svg;
}

// Bepaal kleuren voor dobbelstenen
function getDiceColors($throw) {
    $colors = array_fill(0, 5, "#FFFFFF");
    if (empty($throw)) return $colors;
    
    $frequency = array_count_values($throw);
    foreach ($frequency as $value => $count) {
        if ($count > 1) {
            $color = match($count) {
                2 => "#FFD700", // Goud voor paar
                3 => "#FF6B6B", // Rood voor three of a kind
                4 => "#4ECDC4", // Turquoise voor four of a kind
                5 => "#9B59B6", // Paars voor yahtzee
                default => "#87CEEB" // Lichtblauw voor andere
            };
            
            foreach ($throw as $index => $diceValue) {
                if ($diceValue == $value) {
                    $colors[$index] = $color;
                }
            }
        }
    }
    
    return $colors;
}

// Hoofdlogica
$game = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start'])) {
        // Nieuw spel starten
        $numberOfPlayers = (int)$_POST['players'];
        $game = new SimpleDiceGame($numberOfPlayers);
        $game->startGame();
        $message = "Spel gestart met $numberOfPlayers speler(s)!";
    } elseif (isset($_POST['throw']) && isset($_POST['game_state'])) {
        // Bestaand spel - laad state en gooi dobbelstenen
        $game = SimpleDiceGame::unserialize($_POST['game_state']);
        $game->throwDice();
        $message = "Dobbelstenen gegooid!";
    } elseif (isset($_POST['reset'])) {
        // Reset naar begin
        $game = null;
        $message = "Spel gereset!";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dobbelsteen Spel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>🎲 Dobbelsteen Spel 🎲</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if (!$game || !$game->isGameStarted()): ?>
            <!-- Start scherm -->
            <div class="start-screen">
                <h2>Spel instellingen</h2>
                <form method="post">
                    <label for="players">Aantal spelers:</label>
                    <select name="players" id="players">
                        <option value="1">1 Speler</option>
                        <option value="2">2 Spelers</option>
                    </select>
                    <button type="submit" name="start">Spel Starten</button>
                </form>
            </div>
        
        <?php else: ?>
            <!-- Spel scherm -->
            <div class="game-screen">
                <!-- Huidige speler info -->
                <div class="current-player">
                    <h2>Huidige speler: Speler <?php echo $game->getCurrentPlayer() + 1; ?></h2>
                    <p>Worp <?php echo $game->getCurrentThrow() + 1; ?> van <?php echo $game->getMaxThrows(); ?></p>
                </div>

                <!-- Dobbelstenen -->
                <div class="dice-container">
                    <?php
                    $currentPlayer = $game->getCurrentPlayer();
                    $playerThrows = $game->getThrows($currentPlayer);
                    $lastThrow = end($playerThrows);
                    
                    if ($lastThrow) {
                        $colors = getDiceColors($lastThrow);
                        foreach ($lastThrow as $index => $value) {
                            echo generateDiceSVG($value, $colors[$index]);
                        }
                    } else {
                        // Toon lege dobbelstenen (allemaal 1)
                        for ($i = 0; $i < 5; $i++) {
                            echo generateDiceSVG(1, "#FFFFFF");
                        }
                    }
                    ?>
                </div>

                <!-- Speciale berichten -->
                <?php
                $lastThrow = end($playerThrows);
                if ($lastThrow) {
                    $frequency = array_count_values($lastThrow);
                    $maxCount = max($frequency);
                    
                    if ($maxCount == 5) {
                        echo "<div class='special-message yahtzee'>🎉 YAHTZEE! Alle dobbelstenen zijn gelijk! +50 bonus punten! 🎉</div>";
                    } elseif ($maxCount == 4) {
                        echo "<div class='special-message four-kind'>Four of a kind! +25 bonus punten!</div>";
                    } elseif ($maxCount == 3 && in_array(2, $frequency)) {
                        echo "<div class='special-message full-house'>Full House! +20 bonus punten!</div>";
                    } elseif ($maxCount == 3) {
                        echo "<div class='special-message three-kind'>Three of a kind! +10 bonus punten!</div>";
                    } elseif (count(array_keys($frequency, 2)) == 2) {
                        echo "<div class='special-message two-pairs'>Twee paren! +15 bonus punten!</div>";
                    } elseif ($maxCount == 2) {
                        echo "<div class='special-message one-pair'>Een paar! +5 bonus punten!</div>";
                    }
                }
                ?>

                <!-- Actie knoppen -->
                <div class="actions">
                    <?php if (!$game->isGameFinished()): ?>
                        <form method="post">
                            <input type="hidden" name="game_state" value="<?php echo $game->serialize(); ?>">
                            <button type="submit" name="throw">Dobbelstenen Gooien</button>
                        </form>
                    <?php endif; ?>
                    
                    <form method="post">
                        <button type="submit" name="reset" class="reset">Nieuw Spel</button>
                    </form>
                </div>

                <!-- Scorebord -->
                <div class="scoreboard">
                    <h3>Scorebord</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Speler</th>
                                <?php for ($i = 1; $i <= $game->getMaxThrows(); $i++): ?>
                                    <th>Worp <?php echo $i; ?></th>
                                <?php endfor; ?>
                                <th>Totaal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($player = 0; $player < $game->getPlayers(); $player++): ?>
                                <tr class="<?php echo $player == $game->getCurrentPlayer() ? 'current' : ''; ?>">
                                    <td>Speler <?php echo $player + 1; ?></td>
                                    <?php
                                    $scores = $game->getScores($player);
                                    for ($throw = 0; $throw < $game->getMaxThrows(); $throw++): ?>
                                        <td><?php echo isset($scores[$throw]) ? $scores[$throw] : '-'; ?></td>
                                    <?php endfor; ?>
                                    <td class="total"><?php echo $game->getTotalScore($player); ?></td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Einde spel -->
                <?php if ($game->isGameFinished()): ?>
                    <div class="game-finished">
                        <h2>🎊 Spel Afgelopen! 🎊</h2>
                        <?php $winner = $game->getWinner(); ?>
                        <div class="winner-message">
                            <h3>Winnaar: Speler <?php echo $winner + 1; ?> met <?php echo $game->getTotalScore($winner); ?> punten!</h3>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>