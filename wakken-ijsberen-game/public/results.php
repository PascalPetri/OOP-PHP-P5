<?php

// gemaakt door: pascal
// datum: 10-12-2025

session_start();

require_once '../classes/IceBearsGame.php';

$gamesHistory = $_SESSION['games_history'] ?? [];
$totalCorrect = $_SESSION['total_correct'] ?? 0;
$totalGames = $_SESSION['total_games'] ?? 0;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wakken en de IJsberen - Resultaten</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Wakken en de IJsberen</h1>
            <p class="subtitle">TECHNIEK COLLEGE ROTTERDAM</p>
            <div class="button-group" style="margin-top: 20px;">
                <a href="index.php" class="btn btn-primary">Terug naar Start</a>
                <a href="game.php" class="btn btn-secondary">Terug naar Spel</a>
            </div>
        </header>

        <main>
            <div class="stats-container">
                <div class="stat-box">
                    <h3>Totaal Spellen</h3>
                    <div class="stat-value"><?php echo $totalGames; ?></div>
                </div>
                <div class="stat-box">
                    <h3>Correct Geraden</h3>
                    <div class="stat-value"><?php echo $totalCorrect; ?></div>
                </div>
                <div class="stat-box">
                    <h3>Succespercentage</h3>
                    <div class="stat-value">
                        <?php 
                        if ($totalGames > 0) {
                            echo round(($totalCorrect / $totalGames) * 100) . '%';
                        } else {
                            echo '0%';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <h2>Vorige Spellen</h2>
            
            <?php if (empty($gamesHistory)): ?>
                <p>Nog geen spellen gespeeld.</p>
            <?php else: ?>
                <div class="games-history">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Dobbelstenen</th>
                                <th>Wakken</th>
                                <th>IJsberen</th>
                                <th>Pinguïns</th>
                                <th>Pogingen</th>
                                <th>Fouten</th>
                                <th>Resultaat</th>
                                <th>Tijd</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gamesHistory as $index => $game): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $game['dice_count'] . ' (' . $game['dice_type'] . ')'; ?></td>
                                <td><?php echo $game['holes']; ?></td>
                                <td><?php echo $game['bears']; ?></td>
                                <td><?php echo $game['penguins']; ?></td>
                                <td><?php echo $game['attempts']; ?></td>
                                <td><?php echo $game['wrong_attempts']; ?></td>
                                <td>
                                    <?php if ($game['guessed']): ?>
                                        <span style="color: green; font-weight: bold;">✓ Correct</span>
                                    <?php else: ?>
                                        <span style="color: red;">✗ Niet geraden</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $game['timestamp']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div style="margin-top: 30px; text-align: center;">
                <a href="index.php" class="btn btn-primary">Nieuw Spel Starten</a>
                <a href="game.php" class="btn btn-secondary">Terug naar Spel</a>
            </div>
        </main>
    </div>
</body>
</html>