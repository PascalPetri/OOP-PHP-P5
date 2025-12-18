<?php
//Gemaakt door pascal
// Datum 18-12-2025

require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 <?php echo APP_NAME; ?></h1>
            <p class="subtitle">Veilige toegang met twee-factor authenticatie</p>
        </div>
        
        <?php if (isset($installResult) && $installResult): ?>
            <div class="alert info">
                <?php echo $installResult; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isLoggedIn()): ?>
            <div class="alert success">
                Je bent ingelogd als: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            </div>
            <div class="actions">
                <a href="dashboard.php" class="btn primary">Ga naar Dashboard</a>
                <a href="logout.php" class="btn secondary">Uitloggen</a>
            </div>
        <?php else: ?>
            <div class="welcome">
                <h2>Welkom bij ons beveiligde systeem</h2>
                <p>Kies een optie om verder te gaan:</p>
                
                <div class="action-buttons">
                    <a href="login.php" class="btn primary large">Inloggen</a>
                    <!-- ZORG DAT DEZE LINK KLOPT! -->
                    <a href="register.php" class="btn secondary large">Registreren</a>
                </div>
                
                <div class="info-box">
                    <h3>Hoe werkt 2FA?</h3>
                    <ol>
                        <li>Registreer een account</li>
                        <li>Scan de QR code met Google Authenticator app</li>
                        <li>Log in met gebruikersnaam, wachtwoord + 6-cijferige code</li>
                        <li>Geniet van extra beveiliging!</li>
                    </ol>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>