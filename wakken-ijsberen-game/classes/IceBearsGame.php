<?php

// gemaakt door: pascal
// datum: 10-12-2025

require_once 'DiceFactory.php';

// Hoofd game class voor "Wakken en de IJsberen" spel
class IceBearsGame
{
    // Private properties - alleen toegankelijk binnen deze class
    
    private $diceCount;  // Aantal dobbelstenen in huidige spel
    private $dices = [];  // Array met dobbelsteen objecten
    
    // Huidige game state
    private $currentGame = [
        'holes' => 0,  // Aantal wakken
        'bears' => 0,  // Aantal ijsberen
        'penguins' => 0,  // Aantal pinguïns
        'attempts' => 0,  // Totale aantal pogingen
        'wrongAttempts' => 0,  // Aantal foute pogingen
        'guessed' => false,  // Is het spel correct geraden?
        'solutionShown' => false  // Is de oplossing getoond?
    ];
    
    private $gamesHistory = [];  // Geschiedenis van gespeelde spellen
    private $totalGames = 0;  // Totaal aantal gespeelde spellen
    private $totalCorrect = 0;  // Totaal aantal correct geraden spellen
    private $message = '';  // Bericht voor gebruiker
    private $messageType = '';  // Type bericht (success, error, info)
    
    // Hints voor wanneer speler fouten maakt
    private $hints = [
        "Ijsberen zitten alleen om een wak, zodat ze voedsel kunnen krijgen.",
        "Pinguïns zitten op de zuidpool als er op de noordpool een wak is.",
        "De som van tegenoverliggende zijden is altijd 7 (kubus) of 13 (pentagon).",
        "Alleen oneven worpen hebben een wak in het midden."
    ];

    // Constructor: wordt aangeroepen bij aanmaken van nieuw game object
    public function __construct()
    {
        // Start sessie als deze nog niet gestart is
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Herstel sessie data vanuit $_SESSION
        if (isset($_SESSION['games_history'])) {
            $this->gamesHistory = $_SESSION['games_history'];
        }
        if (isset($_SESSION['total_games'])) {
            $this->totalGames = $_SESSION['total_games'];
        }
        if (isset($_SESSION['total_correct'])) {
            $this->totalCorrect = $_SESSION['total_correct'];
        }
    }

    // NIEUWE METHODE: Laad game state uit sessie
    public function loadGameState()
    {
        if (isset($_SESSION['current_game_state'])) {
            $this->currentGame = $_SESSION['current_game_state'];
        }
        
        // Herstel dobbelsteenwaarden
        $this->restoreDiceValues();
    }

    // NIEUWE METHODE: Zet game state (gebruikt door game.php)
    public function setGameState($state)
    {
        $this->currentGame = $state;
    }

    // NIEUWE METHODE: Herstel game state (volledig herstel)
    public function restoreGameState()
    {
        // Herstel dobbelsteenwaarden uit sessie
        if (isset($_SESSION['dice_values']) && !empty($_SESSION['dice_values'])) {
            $values = $_SESSION['dice_values'];
            
            // Maak dobbelstenen als ze er nog niet zijn
            if (empty($this->dices) && isset($_SESSION['current_game'])) {
                $this->dices = DiceFactory::createMultipleDice(
                    $_SESSION['current_game']['dice_type'],
                    $_SESSION['current_game']['dice_count']
                );
            }
            
            // Zet de waarden terug op de dobbelstenen
            if (!empty($this->dices) && count($this->dices) === count($values)) {
                for ($i = 0; $i < count($this->dices); $i++) {
                    $this->dices[$i]->setValue($values[$i]);
                }
            }
        }
    }

    // Initialiseer een nieuw spel
    public function initializeGame($diceCount, $diceType)
    {
        $this->diceCount = $diceCount;
        $this->dices = DiceFactory::createMultipleDice($diceType, $diceCount);
        
        // Reset game state alleen als er geen actieve game is
        if ($this->currentGame['attempts'] === 0 && $this->currentGame['wrongAttempts'] === 0) {
            $this->currentGame = [
                'holes' => 0,
                'bears' => 0,
                'penguins' => 0,
                'attempts' => 0,
                'wrongAttempts' => 0,
                'guessed' => false,
                'solutionShown' => false
            ];
        }
        
        // Rol de dobbelstenen
        $this->rollDice();
        
        // Sla op
        $this->saveGameState();
        
        return true;
    }

    // Gooi alle dobbelstenen opnieuw
    public function rollDice()
    {
        // Reset bij nieuwe worp
        $this->currentGame['attempts'] = 0;
        $this->currentGame['wrongAttempts'] = 0;
        $this->currentGame['guessed'] = false;
        $this->currentGame['solutionShown'] = false;
        
        $holes = 0;
        $bears = 0;
        $penguins = 0;

        // Gooi elke dobbelsteen en bereken waarden
        foreach ($this->dices as $dice) {
            $dice->roll();
            if ($dice->hasHole()) {
                $holes++;
                $bears += $dice->getBears();
                $penguins += $dice->getPenguins();
            }
        }

        // Sla berekende waarden op
        $this->currentGame['holes'] = $holes;
        $this->currentGame['bears'] = $bears;
        $this->currentGame['penguins'] = $penguins;

        // Sla dobbelsteenwaarden op
        $this->saveDiceValues();
        $this->saveGameState();

        return true;
    }

    // Sla dobbelsteenwaarden op in sessie
    private function saveDiceValues()
    {
        if (!empty($this->dices)) {
            $values = [];
            foreach ($this->dices as $dice) {
                $values[] = $dice->getValue();
            }
            $_SESSION['dice_values'] = $values;
        }
    }

    // Herstel dobbelsteenwaarden vanuit sessie
    private function restoreDiceValues()
    {
        if (isset($_SESSION['dice_values']) && !empty($_SESSION['dice_values'])) {
            $values = $_SESSION['dice_values'];

            // Als er geen dobbelstenen zijn, maak ze aan
            if (empty($this->dices)) {
                if (isset($_SESSION['current_game'])) {
                    $this->dices = DiceFactory::createMultipleDice(
                        $_SESSION['current_game']['dice_type'],
                        $_SESSION['current_game']['dice_count']
                    );
                }
            }

            // Zet de waarden op de dobbelstenen
            if (!empty($this->dices) && count($this->dices) === count($values)) {
                for ($i = 0; $i < count($this->dices); $i++) {
                    if (method_exists($this->dices[$i], 'setValue')) {
                        $this->dices[$i]->setValue($values[$i]);
                    }
                }
            }
        }
    }

    // Verwerk een gok van de speler
    public function makeGuess($holesGuess, $bearsGuess, $penguinsGuess)
    {
        if ($this->currentGame['guessed'] || $this->currentGame['solutionShown']) {
            $this->setMessage("Je mag niet meer raden voor deze worp!", "error");
            return false;
        }

        if ($this->currentGame['wrongAttempts'] >= 3) {
            $this->setMessage("Je hebt 3 fouten gemaakt! Start een nieuw spel.", "error");
            return false;
        }

        $this->currentGame['attempts']++;

        // Controleer of de gok correct is
        if ($holesGuess == $this->currentGame['holes'] &&
            $bearsGuess == $this->currentGame['bears'] &&
            $penguinsGuess == $this->currentGame['penguins']) {

            $this->currentGame['guessed'] = true;
            $this->totalCorrect++;
            $this->setMessage("Correct geraden! Goed gedaan!", "success");

            $this->saveGameToHistory();
            $this->saveGameState();

            return true;
        } else {
            $this->currentGame['wrongAttempts']++;

            if ($this->currentGame['wrongAttempts'] >= 3) {
                $hint = $this->hints[array_rand($this->hints)];
                $this->setMessage("Fout geraden. Je hebt 3 fouten gemaakt. Hint: $hint", "error");
                $this->currentGame['solutionShown'] = true;
                $this->saveGameToHistory();
            } else {
                $remaining = 3 - $this->currentGame['wrongAttempts'];
                $this->setMessage("Helaas fout geraden. Je hebt nog $remaining pogingen over.", "error");
            }

            $this->saveGameState();
            return false;
        }
    }

    // Toon de oplossing
    public function showSolution()
    {
        $this->currentGame['solutionShown'] = true;
        $this->setMessage("Oplossing getoond. Je mag niet meer raden voor deze worp.", "info");

        if (!$this->currentGame['guessed']) {
            $this->saveGameToHistory();
        }

        $this->saveGameState();
    }

    // Sla huidige spel op in geschiedenis
    private function saveGameToHistory()
    {
        if (!empty($this->dices)) {
            $gameResult = [
                'dice_count' => $this->diceCount,
                'dice_type' => $this->dices[0]->getType(),
                'holes' => $this->currentGame['holes'],
                'bears' => $this->currentGame['bears'],
                'penguins' => $this->currentGame['penguins'],
                'attempts' => $this->currentGame['attempts'],
                'wrong_attempts' => $this->currentGame['wrongAttempts'],
                'guessed' => $this->currentGame['guessed'],
                'timestamp' => date('Y-m-d H:i:s')
            ];

            array_unshift($this->gamesHistory, $gameResult);
            $this->totalGames++;

            // Beperk geschiedenis tot 10 spellen
            if (count($this->gamesHistory) > 10) {
                array_pop($this->gamesHistory);
            }
        }
    }

    // Sla complete game state op in sessie
    public function saveGameState()
    {
        $_SESSION['games_history'] = $this->gamesHistory;
        $_SESSION['total_games'] = $this->totalGames;
        $_SESSION['total_correct'] = $this->totalCorrect;
        $_SESSION['current_game_state'] = $this->currentGame;

        if (!empty($this->dices)) {
            $this->saveDiceValues();
        }
    }

    // Haal dobbelstenen op
    public function getDices()
    {
        $this->restoreDiceValues();
        return $this->dices;
    }

    // Haal dobbelsteen waarden op
    public function getDiceValues()
    {
        $values = [];
        $dices = $this->getDices();
        foreach ($dices as $dice) {
            $values[] = $dice->getValue();
        }
        return $values;
    }

    // GETTER METHODS voor game state
    
    public function getHoles() { return $this->currentGame['holes']; }
    public function getBears() { return $this->currentGame['bears']; }
    public function getPenguins() { return $this->currentGame['penguins']; }
    public function getAttempts() { return $this->currentGame['attempts']; }
    public function getWrongAttempts() { return $this->currentGame['wrongAttempts']; }
    public function isGuessed() { return $this->currentGame['guessed']; }
    public function isSolutionShown() { return $this->currentGame['solutionShown']; }
    public function getMaxDiceCount() { return $this->diceCount; }
    public function hasMessage() { return !empty($this->message); }
    public function getMessage() { return $this->message; }
    public function getMessageType() { return $this->messageType; }
    
    // PRIVATE: Stel bericht in
    private function setMessage($message, $type = 'info') { 
        $this->message = $message; 
        $this->messageType = $type; 
    }
    
    // Wis bericht
    public function clearMessage() { 
        $this->message = ''; 
        $this->messageType = ''; 
    }
    
    // GETTERS voor statistieken
    public function getGamesHistory() { return $this->gamesHistory; }
    public function getTotalGames() { return $this->totalGames; }
    public function getTotalCorrect() { return $this->totalCorrect; }
    
    // Reset hele spel
    public function resetAll() {
        $this->initializeGame($this->diceCount, !empty($this->dices) ? $this->dices[0]->getType() : 'cube');
    }
}