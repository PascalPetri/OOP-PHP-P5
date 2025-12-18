<?php

//Gemaakt door pascal
// Datum 18-12-2025

// login.php - WORKING VERSION
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
        
        public function verifyCode($secret, $code, $tolerance = 1) {
            $time = floor(time() / 30);
            
            for ($i = -$tolerance; $i <= $tolerance; $i++) {
                $calculated = $this->getCode($secret, $time + $i);
                if ($calculated === (string)$code) {
                    return true;
                }
            }
            return false;
        }
        
        public function getCode($secret, $time = null) {
            if ($time === null) {
                $time = floor(time() / 30);
            }
            
            // Base32 decode
            $key = $this->base32_decode($secret);
            
            // Time as binary
            $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $time);
            
            // Hash
            $hm = hash_hmac('SHA1', $time, $key, true);
            $offset = ord(substr($hm, -1)) & 0x0F;
            $hashpart = substr($hm, $offset, 4);
            $value = unpack('N', $hashpart);
            $value = $value[1] & 0x7FFFFFFF;
            $modulo = pow(10, $this->codeLength);
            
            return str_pad($value % $modulo, $this->codeLength, '0', STR_PAD_LEFT);
        }
        
        private function base32_decode($secret) {
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
            $secret = strtoupper($secret);
            $secret = str_replace('=', '', $secret);
            $binary = '';
            
            for ($i = 0; $i < strlen($secret); $i++) {
                $char = $secret[$i];
                $value = strpos($chars, $char);
                $binary .= str_pad(decbin($value), 5, '0', STR_PAD_LEFT);
            }
            
            $bytes = '';
            for ($i = 0; $i < strlen($binary); $i += 8) {
                $byte = substr($binary, $i, 8);
                $bytes .= chr(bindec($byte));
            }
            
            return $bytes;
        }
    }
}

$ga = new GoogleAuthenticator();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $code = $_POST['code'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = "Vul gebruikersnaam en wachtwoord in";
    } else {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Check 2FA
                if (!empty($user['twofa_secret'])) {
                    if (empty($code)) {
                        $error = "Voer je Google Authenticator code in";
                    } elseif ($ga->verifyCode($user['twofa_secret'], $code, 2)) {
                        // Login successful
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['logged_in'] = true;
                        $_SESSION['login_time'] = time();
                        
                        redirect('dashboard.php');
                    } else {
                        $error = "Ongeldige 2FA code";
                    }
                } else {
                    // No 2FA setup
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['login_time'] = time();
                    
                    redirect('dashboard.php');
                }
            } else {
                $error = "Ongeldige inloggegevens";
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
    <title>Inloggen - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔑 Inloggen</h1>
            <p class="subtitle">Veilige toegang met twee-factor authenticatie</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
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
            </div>
            
            <div class="form-group">
                <label for="code">Google Authenticator Code (optioneel):</label>
                <input type="text" id="code" name="code" placeholder="000000" maxlength="6">
                <small>Alleen nodig als je 2FA hebt ingesteld</small>
            </div>
            
            <button type="submit" class="btn primary">Inloggen</button>
        </form>
        
        <div class="links">
            <p>Nog geen account? <a href="register.php">Registreer hier</a></p>
            <p><a href="index.php">← Terug naar home</a></p>
        </div>
    </div>
</body>
</html>