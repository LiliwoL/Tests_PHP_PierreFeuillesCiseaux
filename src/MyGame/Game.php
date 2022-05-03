<?php

declare(strict_types=1);

namespace MyGame;

use MyGame\Player\IPlayer;
use MyGame\Rules\IRules;
use RuntimeException;

class Game
{
    private IRules $rules;
    private IPlayer $player1;
    private IPlayer $player2;
    private int $score = 0;

    public function __construct(IRules $rules, IPlayer $player1, IPlayer $player2)
    {
        $this->rules = $rules;
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public function play(int $turns): void
    {
        $currentTurn = 0;
        while ($currentTurn < $turns || 0 === $this->getScore()) {
            $this->score += $this->playOneMove();
            ++$currentTurn;
        }
    }

    public function playOneMove(): int
    {
        $player1Move = $this->player1->playOneMove($this->rules);
        $player2Move = $this->player2->playOneMove($this->rules);
        $point = $this->rules->compare($player1Move, $player2Move);

        echo "{$player1Move} {$player2Move} --> {$point}\n";

        return $point;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function winner(): IPlayer
    {
        $score = $this->getScore();
        if ($score > 0) {
            return $this->player1;
        }
        if ($score < 0) {
            return $this->player2;
        }
        throw new RuntimeException('No winner');
    }
}

