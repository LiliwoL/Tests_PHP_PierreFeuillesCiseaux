<?php

declare(strict_types=1);

namespace MyGame\Player;

use MyGame\Rules\IRules;

interface IPlayer
{
    public function getName(): string;

    public function playOneMove(IRules $rules): string;
}

