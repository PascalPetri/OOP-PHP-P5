<?php
// Zet error reporting aan
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once '../classes/Dice.php';
require_once '../classes/CubeDice.php';
require_once '../classes/PentagonDice.php';
require_once '../classes/IceBearsGame.php';
require_once '../classes/DiceFactory.php';

// Controleer of we een game hebben
if (!isset($_SESSION['current_game'])) {
    header('Location: index.php');
    exit;
}

// Game object maken
$game = new IceBearsGame();

// LAAD game state uit sessie in plaats van opnieuw initialiseren
// LAAD game state uit sessie in plaats van opnieuw initialiseren
$diceCount = $_SESSION['current_game']['dice_count'];
$diceType = $_SESSION['current_game']['dice_type'];

// Controleer of er al een game state is
if (isset($_SESSION['current_game_state'])) {
    // Herstel de bestaande game state
    $gameState = $_SESSION['current_game_state'];
    
    // Zet de game state terug in het object
    $game->setGameState($gameState);
    
    // Maak alleen dobbelstenen als ze er nog niet zijn
    if (empty($_SESSION['dice_values'])) {
        $game->initializeGame($diceCount, $diceType);
    } else {
        // Herstel dobbelsteenwaarden
        $game->restoreGameState();
    }
} else {
    // Eerste keer: initialiseer nieuwe game
    $game->initializeGame($diceCount, $diceType);
}

// Verwerk POST acties
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['roll'])) {
        $game->rollDice();
        $game->clearMessage();
    }
    
    if (isset($_POST['guess'])) {
        $holesGuess = isset($_POST['holes']) ? (int)$_POST['holes'] : 0;
        $bearsGuess = isset($_POST['bears']) ? (int)$_POST['bears'] : 0;
        $penguinsGuess = isset($_POST['penguins']) ? (int)$_POST['penguins'] : 0;
        $game->makeGuess($holesGuess, $bearsGuess, $penguinsGuess);
    }
    
    if (isset($_POST['show_solution'])) {
        $game->showSolution();
    }
}

// Sla game state op
$game->saveGameState();

// Haal dobbelsteen data op
$dices = $game->getDices();
$diceValues = [];
if (!empty($dices)) {
    foreach ($dices as $dice) {
        $diceValues[] = $dice->getValue();
    }
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wakken en de IJsberen - Spel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Wakken en de IJsberen</h1>
            <p class="subtitle"></p>
            
            <div class="game-status">
                <div class="status-item">
                    <div class="status-label">Speler</div>
                    <div class="status-value"><?php echo htmlspecialchars($_SESSION['current_game']['player_name']); ?></div>
                </div>
                <div class="status-item">
                    <div class="status-label">Dobbelstenen</div>
                    <div class="status-value">
                        <?php 
                        echo $_SESSION['current_game']['dice_count'] . ' ('; 
                        echo ($_SESSION['current_game']['dice_type'] == 'cube' ? 'Kubus' : 'Pentagon'); 
                        echo ')';
                        ?>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-label">Game #</div>
                    <div class="status-value">
                        <?php 
                        echo isset($_SESSION['total_games']) ? $_SESSION['total_games'] + 1 : 1;
                        ?>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <?php if ($game->hasMessage()): ?>
                <div class="message <?php echo $game->getMessageType(); ?>">
                    <?php echo $game->getMessage(); ?>
                </div>
            <?php endif; ?>

            <!-- Dobbelstenen weergave -->
            <div class="dice-container">
                <?php 
                if (!empty($dices) && !empty($diceValues)):
                    foreach ($dices as $index => $dice): 
                        $value = isset($diceValues[$index]) ? $diceValues[$index] : 0;
                        $hasHole = $dice->hasHole();
                        $diceTypeClass = $dice->getType();
                ?>
                    <div class="dice <?php echo $hasHole ? 'hole' : ''; ?> <?php echo $diceTypeClass; ?>">
                        <?php if ($diceTypeClass == 'cube'): ?>
                            <div class="dice-dots">
                                <?php
                                $dotPositions = [
                                    1 => [5],
                                    2 => [1, 9],
                                    3 => [1, 5, 9],
                                    4 => [1, 3, 7, 9],
                                    5 => [1, 3, 5, 7, 9],
                                    6 => [1, 3, 4, 6, 7, 9]
                                ];
                                
                                $positions = isset($dotPositions[$value]) ? $dotPositions[$value] : [];
                                if (!empty($positions)) {
                                    for ($i = 1; $i <= 9; $i++):
                                        if (in_array($i, $positions)):
                                ?>
                                    <div class="dot pos-<?php echo $i; ?>"></div>
                                <?php 
                                        endif;
                                    endfor; 
                                } else {
                                    echo '<div class="dice-value">' . $value . '</div>';
                                }
                                ?>
                            </div>
                        <?php else: ?>
                            <div class="dice-value"><?php echo $value; ?></div>
                        <?php endif; ?>
                    </div>
                <?php 
                    endforeach; 
                else: ?>
                    <p class="error">Geen dobbelstenen beschikbaar.</p>
                <?php endif; ?>
            </div>

            <!-- Gok formulier -->
            <form method="POST" class="game-form" id="guessForm">
                <h3>Raad de aantallen:</h3>
                
                <div class="result-grid">
                    <div class="form-group">
                        <label for="holes">Aantal Wakken:</label>
                        <input type="number" id="holes" name="holes" min="0" max="<?php echo $_SESSION['current_game']['dice_count']; ?>" 
                               required value="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="bears">Aantal IJsberen:</label>
                        <input type="number" id="bears" name="bears" min="0" 
                               required value="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="penguins">Aantal Pinguïns:</label>
                        <input type="number" id="penguins" name="penguins" min="0" 
                               required value="0">
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" name="guess" class="btn btn-primary">
                        Raad
                    </button>
                    
                    <button type="submit" name="show_solution" class="btn btn-secondary">
                        Toon Oplossing
                    </button>
                    
                    <button type="submit" name="roll" class="btn btn-success">
                        Nieuwe Worp
                    </button>
                    
                    <a href="index.php" class="btn btn-danger">Nieuw Spel</a>
                    <a href="restart.php" class="btn btn-secondary">Reset Alles</a>
                </div>
            </form>

            <script>
            document.getElementById('guessForm').addEventListener('submit', function(e) {
                var holes = document.getElementById('holes').value;
                var bears = document.getElementById('bears').value;
                var penguins = document.getElementById('penguins').value;
                
                if (holes === '' || bears === '' || penguins === '') {
                    alert('Vul alle velden in!');
                    e.preventDefault();
                    return false;
                }
                
                return true;
            });
            </script>

            <!-- Huidige game status -->
            <div class="results">
                <h3>Huidige Game Status:</h3>
                <div class="result-grid">
                    <div class="result-item">
                        <div class="result-label">Fout geraden:</div>
                        <div class="result-value"><?php echo $game->getWrongAttempts(); ?> / 3</div>
                    </div>
                    <div class="result-item">
                        <div class="result-label">Pogingen:</div>
                        <div class="result-value"><?php echo $game->getAttempts(); ?></div>
                    </div>
                    <div class="result-item">
                        <div class="result-label">Status:</div>
                        <div class="result-value">
                            <?php 
                            if ($game->isGuessed()) {
                                echo "Correct!";
                            } elseif ($game->isSolutionShown()) {
                                echo "Oplossing getoond";
                            } else {
                                echo "Bezig...";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Oplossing (alleen tonen als opgevraagd of geraden) -->
            <?php if ($game->isGuessed() || $game->isSolutionShown()): ?>
            <div class="results">
                <h3>Oplossing:</h3>
                <div class="result-grid">
                    <div class="result-item">
                        <div class="result-label">Wakken:</div>
                        <div class="result-value"><?php echo $game->getHoles(); ?></div>
                    </div>
                    <div class="result-item">
                        <div class="result-label">IJsberen:</div>
                        <div class="result-value"><?php echo $game->getBears(); ?></div>
                    </div>
                    <div class="result-item">
                        <div class="result-label">Pinguïns:</div>
                        <div class="result-value"><?php echo $game->getPenguins(); ?></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>