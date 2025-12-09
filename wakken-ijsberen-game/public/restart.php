<?php
session_start();

// Reset alle sessie variabelen
$_SESSION = array();

// Verwijder sessie cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Vernietig sessie
session_destroy();

// Redirect naar startpagina
header('Location: index.php');
exit;
?>