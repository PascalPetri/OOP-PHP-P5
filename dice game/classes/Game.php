<?php
// gemaakt door pascal petri
// datum 26-11-2025
session_start();

// Eerst alle classes includeren VOOR we de sessie gebruiken
require_once 'classes/Dice.php';
require_once 'classes/Player.php';
require_once 'classes/Game.php';

// Initialize game
if (!isset($_SESSION['game'])) {
    $numberOfPlayers = isset($_POST['players']) ? (int)$_POST['players'] : 1;
    $_SESSION['game'] = new Game($numberOfPlayers);
}

$game = $_SESSION['game'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start'])) {
        $numberOfPlayers = isset($_POST['players']) ? (int)$_POST['players'] : 1;
        $_SESSION['game'] = new Game($numberOfPlayers);
        $game = $_SESSION['game'];
        $game->startGame();
    } elseif (isset($_POST['throw'])) {
        $game->throwDice();
        $_SESSION['game'] = $game;
    } elseif (isset($_POST['reset'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
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
        
        <?php if (!$game->isGameStarted()): ?>
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
                    <h2>Huidige speler: <?php echo htmlspecialchars($game->getCurrentPlayer()->getName()); ?></h2>
                    <p>Worp <?php echo $game->getCurrentPlayer()->getThrowCount(); ?> van <?php echo $game->getMaxThrows(); ?></p>
                </div>

                <!-- Dobbelstenen -->
                <div class="dice-container">
                    <?php
                    $currentPlayer = $game->getCurrentPlayer();
                    $dice = $currentPlayer->getDice();
                    foreach ($dice as $die): ?>
                        <?php echo $die->getSvg(); ?>
                    <?php endforeach; ?>
                </div>

                <!-- Speciale berichten -->
                <?php
                $currentThrow = $currentPlayer->getCurrentThrow();
                if (!empty($currentThrow)) {
                    $frequency = array_count_values($currentThrow);
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
                    <?php if (!$game->isGameFinished() && $currentPlayer->getThrowCount() < $game->getMaxThrows()): ?>
                        <form method="post">
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
                            <?php foreach ($game->getPlayers() as $player): ?>
                                <tr class="<?php echo $player === $game->getCurrentPlayer() ? 'current' : ''; ?>">
                                    <td><?php echo htmlspecialchars($player->getName()); ?></td>
                                    <?php
                                    $scores = $player->getScores();
                                    for ($i = 0; $i < $game->getMaxThrows(); $i++): ?>
                                        <td><?php echo isset($scores[$i]) ? $scores[$i] : '-'; ?></td>
                                    <?php endfor; ?>
                                    <td class="total"><?php echo $player->getTotalScore(); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Einde spel -->
                <?php if ($game->isGameFinished()): ?>
                    <div class="game-finished">
                        <h2>🎊 Spel Afgelopen! 🎊</h2>
                        <?php $winner = $game->getWinner(); ?>
                        <div class="winner-message">
                            <h3>Winnaar: <?php echo htmlspecialchars($winner->getName()); ?> met <?php echo $winner->getTotalScore(); ?> punten!</h3>
                        </div>
                        
                        <!-- Statistieken -->
                        <div class="statistics">
                            <h3>Spel Statistieken</h3>
                            <?php $stats = $game->getGameStatistics(); ?>
                            <ul>
                                <?php foreach ($stats as $stat): ?>
                                    <li>
                                        <strong><?php echo htmlspecialchars($stat['name']); ?>:</strong>
                                        Totaal: <?php echo $stat['totalScore']; ?> punten |
                                        Gemiddeld: <?php echo $stat['averagePerThrow']; ?> punten per worp
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>