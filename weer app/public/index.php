<?php
// public/index.php

$apiUrl = "https://api.open-meteo.com/v1/forecast?latitude=52.3676&longitude=4.9041&current_weather=true";

$weather = null;
$error = null;

try {
    $response = file_get_contents($apiUrl);
    if ($response === false) {
        throw new Exception("Kan de weersdata niet ophalen");
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Fout bij het decoderen van JSON");
    }

    if (!isset($data['current_weather'])) {
        throw new Exception("Geen huidige weersdata beschikbaar");
    }

    $weather = $data['current_weather'];

} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Weer in Amsterdam</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="weather-box">
        <h1>Weer in Amsterdam</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($weather): ?>
            <p>🌡 Temperatuur: <?= htmlspecialchars($weather['temperature']) ?>°C</p>
            <p>💨 Windsnelheid: <?= htmlspecialchars($weather['windspeed']) ?> km/h</p>
        <?php else: ?>
            <p class="loading">Laden van weerdata…</p>
        <?php endif; ?>
    </div>
</body>
</html>
