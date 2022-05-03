<?php

declare(strict_types=1);

namespace MyGame\Player;

use MyGame\Rules\IRules;

class Computer extends Player
{
    public function playOneMove(IRules $rules): string
    {
        $gestures = $rules->getGestures();

        return $gestures[array_rand($gestures)];
    }
}

