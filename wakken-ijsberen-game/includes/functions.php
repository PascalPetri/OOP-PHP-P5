<?php
/**
 * Helper functies voor het spel
 */

/**
 * Zorgt ervoor dat een getal binnen een bereik blijft
 */
function clamp($value, $min, $max) {
    return max($min, min($max, $value));
}

/**
 * Formatteert een getal met duizendtal scheiding
 */
function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}

/**
 * Retourneert de juiste meervoudsvorm
 */
function pluralize($count, $singular, $plural = null) {
    if ($plural === null) {
        $plural = $singular . 'en';
    }
    return $count == 1 ? $singular : $plural;
}

/**
 * Toont een succes bericht
 */
function showSuccess($message) {
    echo '<div class="message success">' . htmlspecialchars($message) . '</div>';
}

/**
 * Toont een fout bericht
 */
function showError($message) {
    echo '<div class="message error">' . htmlspecialchars($message) . '</div>';
}

/**
 * Toont een info bericht
 */
function showInfo($message) {
    echo '<div class="message info">' . htmlspecialchars($message) . '</div>';
}

/**
 * Redirect naar een andere pagina
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Controleert of een POST request is gedaan
 */
function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Haalt een waarde op uit POST of geeft default terug
 */
function post($key, $default = '') {
    return $_POST[$key] ?? $default;
}

/**
 * Haalt een waarde op uit GET of geeft default terug
 */
function get($key, $default = '') {
    return $_GET[$key] ?? $default;
}

/**
 * Maakt een random hint aan
 */
function getRandomHint() {
    $hints = [
        "Ijsberen zitten alleen om een wak, zodat ze voedsel kunnen krijgen.",
        "Pinguïns zitten op de zuidpool als er op de noordpool een wak is.",
        "De som van tegenoverliggende zijden is altijd 7 (kubus) of 13 (pentagon).",
        "Alleen oneven worpen hebben een wak in het midden.",
        "Wakken zijn altijd in het midden van de dobbelsteen.",
        "Hoe meer wakken, hoe meer ijsberen er kunnen zijn.",
        "Elk wak heeft zijn eigen groep ijsberen.",
        "Pinguïns komen alleen voor als er wakken zijn."
    ];
    return $hints[array_rand($hints)];
}

/**
 * Toont een dobbelsteen met punten
 */
function displayDice($value, $hasHole = false, $type = 'cube') {
    $class = 'dice';
    if ($hasHole) {
        $class .= ' hole';
    }
    if ($type === 'pentagon') {
        $class .= ' pentagon';
    }
    
    echo '<div class="' . $class . '">';
    
    if ($type === 'cube') {
        echo '<div class="dice-dots">';
        $dotPositions = [
            1 => [5],
            2 => [1, 9],
            3 => [1, 5, 9],
            4 => [1, 3, 7, 9],
            5 => [1, 3, 5, 7, 9],
            6 => [1, 3, 4, 6, 7, 9]
        ];
        
        $positions = $dotPositions[$value] ?? [];
        for ($i = 1; $i <= 9; $i++) {
            if (in_array($i, $positions)) {
                echo '<div class="dot pos-' . $i . '"></div>';
            }
        }
        echo '</div>';
    } else {
        echo '<div class="dice-value">' . $value . '</div>';
    }
    
    echo '</div>';
}

/**
 * Bereken het succespercentage
 */
function calculateSuccessRate($totalGames, $correctGuesses) {
    if ($totalGames == 0) {
        return 0;
    }
    return round(($correctGuesses / $totalGames) * 100);
}