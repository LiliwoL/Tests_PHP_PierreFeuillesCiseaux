<?php

declare(strict_types=1);

namespace MyGame\Player;

use MyGame\Rules\IRules;

class Human extends Player
{
    protected function prompt(array $gestures): void
    {
        echo "Saisissez votre geste ({$this->showPossibilities($gestures)}) : ";
    }

    protected function showPossibilities(array $gestures): string
    {
        return implode(', ', $gestures);
    }

    /**
     * @codeCoverageIgnore Too simple to be tested, but tricky to test
     */
    protected function getInput(): string
    {
        return mb_strtoupper(readline());
    }

    public function playOneMove(IRules $rules): string
    {
        $correctInput = false;
        $input = null;
        while (!$correctInput) {
            $this->prompt($rules->getGestures());
            $input = $this->getInput();
            if ($rules->checkGesture($input)) {
                $correctInput = true;
            }
        }

        return $input;
    }
}

