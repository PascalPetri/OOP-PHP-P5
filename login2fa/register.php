<?php

//Gemaakt pascal
// Datum 18-12-2025

// registreren.php - WORKING VERSION
require_once 'config.php';

// Maak de GoogleAuthenticator class als deze niet bestaat
if (!class_exists('GoogleAuthenticator')) {
    class GoogleAuthenticator {
        protected $codeLength = 6;
        
        public function createSecret($length = 16) {
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
            $secret = '';
            for ($i = 0; $i < $length; $i++) {
                $secret .= $chars[rand(0, 31)];
            }
            return $secret;
        }
        
        public function getQRCodeGoogleUrl($name, $secret) {
            $urlencoded = urlencode('otpauth://totp/' . $name . '?secret=' . $secret . '&issuer=2FA%20SYSTEEM');
            return 'https://quickchart.io/qr?text=' . $urlencoded . '&size=200';
        }
    }
}

$ga = new GoogleAuthenticator();
$error = '';
$success = '';
$qrCode = '';
$secret = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = "Vul alle velden in";
    } elseif (strlen($password) < 6) {
        $error = "Wachtwoord moet minimaal 6 tekens hebben";
    } elseif ($password !== $confirm) {
        $error = "Wachtwoorden komen niet overeen";
    } else {
        try {
            $pdo = getDB();
            
            // Check if user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Deze gebruikersnaam is al in gebruik";
            } else {
                // Create new user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $secret = $ga->createSecret();
                
                $stmt = $pdo->prepare("INSERT INTO users (username, password, twofa_secret) VALUES (?, ?, ?)");
                $stmt->execute([$username, $hashedPassword, $secret]);
                
                // Generate QR code
                $qrCode = $ga->getQRCodeGoogleUrl($username, $secret);
                $success = "Account succesvol aangemaakt!";
            }
        } catch(PDOException $e) {
            $error = "Database fout: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Registreren</h1>
            <p class="subtitle">Maak een nieuw beveiligd account</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label for="username">Gebruikersnaam:</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" required>
                <small>Minimaal 6 tekens</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Bevestig wachtwoord:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn primary">Account aanmaken</button>
        </form>
        
        <?php if ($qrCode): ?>
            <div class="qr-section">
                <h3>📱 2FA Instellen</h3>
                <p>Scan deze QR code met Google Authenticator:</p>
                <img src="<?php echo htmlspecialchars($qrCode); ?>" alt="QR Code" class="qr-code">
                
                <div class="secret-box">
                    <p><strong>Geheime code (backup):</strong></p>
                    <code><?php echo htmlspecialchars($secret); ?></code>
                    <p class="warning">⚠️ Bewaar deze code veilig voor herstel!</p>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="links">
            <p>Al een account? <a href="login.php">Inloggen</a></p>
            <p><a href="index.php">← Terug naar home</a></p>
        </div>
    </div>
</body>
</html>