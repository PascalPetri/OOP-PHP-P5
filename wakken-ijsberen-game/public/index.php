<?php

// gemaakt door: pascal
// datum: 10-12-2025

session_start();

// Includes naar ../classes/
require_once '../classes/Dice.php';
require_once '../classes/CubeDice.php';
require_once '../classes/PentagonDice.php';
require_once '../classes/IceBearsGame.php';
require_once '../classes/DiceFactory.php';

// Zorg dat we een game kunnen starten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dice_count'])) {
    $_SESSION['current_game'] = [
        'dice_count' => (int)$_POST['dice_count'],
        'dice_type' => $_POST['dice_type'],
        'player_name' => $_POST['player_name'] ?? 'Anoniem'
    ];
    
    // Direct doorsturen naar game.php
    header('Location: game.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wakken en de IJsberen - Start</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Wakken en de IJsberen</h1>
        </header>

        <main class="start-screen">
            <div class="game-info">
                <h2>Welkom bij het spel!</h2>
                <div class="rules">
                    <h3>Spelregels:</h3>
                    <br>
                    <ul>
                        <li>Kies hoeveel dobbelstenen je wilt gooien (3-8)</li>
                        <li>Kies het type dobbelsteen (kubus of pentagon)</li>
                        <li>Raad het aantal wakken, ijsberen en pinguïns</li>
                        <li>Ijsberen zitten alleen rond wakken</li>
                        <li>Pinguïns zijn op de zuidpool (tegenovergestelde kant)</li>
                        <li>Na 3 foute pogingen krijg je een hint</li>
                    </ul>
                </div>

                <?php if (isset($_SESSION['total_games']) && $_SESSION['total_games'] > 0): ?>
                <div class="stats">
                    <h3>Je statistieken:</h3>
                    <p>Totaal gespeeld: <?php echo $_SESSION['total_games']; ?> spellen</p>
                    <p>Correct geraden: <?php echo $_SESSION['total_correct'] ?? 0; ?> keer</p>
                </div>
                <?php endif; ?>
            </div>

            <form method="POST" class="game-setup">
                <div class="form-group">
                    <label for="dice-count">Aantal dobbelstenen:</label>
                    <select name="dice_count" id="dice-count" required>
                        <option value="">Kies aantal...</option>
                        <?php for ($i = 3; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?> dobbelstenen</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="dice-type">Type dobbelsteen:</label>
                    <select name="dice_type" id="dice-type" required>
                        <option value="">Kies type...</option>
                        <option value="cube">Kubus (6 zijden)</option>
                        <option value="pentagon">Pentagon (12 zijden)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="player-name">Je naam (optioneel):</label>
                    <input type="text" name="player_name" id="player-name" placeholder="Voer je naam in">
                </div>

                <button type="submit" class="btn-start">Start Spel</button>
            </form>

            <?php if (isset($_SESSION['games_history']) && count($_SESSION['games_history']) > 0): ?>
            <div class="previous-games">
                <h3>Vorige spellen:</h3>
                <a href="results.php" class="btn-view-results">Bekijk alle resultaten</a>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>