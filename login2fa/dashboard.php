<?php

//Gemaakt door pascal
// Datum 18-12-2025

require_once 'config.php';

// Check login
if (!isLoggedIn()) {
    redirect('login.php');
}

// Session timeout (1 hour)
$timeout = 3600;
if (time() - $_SESSION['login_time'] > $timeout) {
    session_destroy();
    redirect('login.php?timeout=1');
}

// Update login time
$_SESSION['login_time'] = time();

// Get user
try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    die("Database fout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏠 Dashboard</h1>
            <p class="subtitle">Welkom, <?php echo htmlspecialchars($user['username']); ?>!</p>
        </div>
        
        <div class="alert success">
            ✅ Je bent succesvol ingelogd met twee-factor authenticatie
        </div>
        
        <div class="user-info">
            <h2>Account Informatie</h2>
            <table>
                <tr>
                    <td><strong>Gebruikersnaam:</strong></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
                <tr>
                    <td><strong>Account aangemaakt:</strong></td>
                    <td><?php echo date('d-m-Y H:i', strtotime($user['created_at'])); ?></td>
                </tr>
                <tr>
                    <td><strong>2FA Status:</strong></td>
                    <td>
                        <?php if (!empty($user['twofa_secret'])): ?>
                            <span class="status active">✅ Ingesteld</span>
                        <?php else: ?>
                            <span class="status inactive">❌ Niet ingesteld</span>
                            <p><small><a href="registreren.php?setup2fa=1">2FA instellen</a></small></p>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="actions">
            <a href="logout.php" class="btn primary">Uitloggen</a>
            <a href="index.php" class="btn secondary">Terug naar Home</a>
        </div>
    </div>
</body>
</html>