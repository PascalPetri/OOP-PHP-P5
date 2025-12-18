<?php

//Gemaakt pascal
// Datum 18-12-2025

require_once 'config.php';

// Log out
session_unset();
session_destroy();

// Redirect to login
redirect('login.php?loggedout=1');
?>