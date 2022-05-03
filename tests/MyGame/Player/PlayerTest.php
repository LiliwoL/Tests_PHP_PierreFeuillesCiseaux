<?php
declare(strict_types=1);

namespace Tests\MyGame\Player;

use PHPUnit\Framework\TestCase;
use MyGame\Rules\IRules;
use MyGame\Player\Player;

class PlayerTest extends TestCase
{
    private Player $player;

    public function setUp(): void
    {
        $this->player = new class('Bob') extends Player {
            public function playOneMove(IRules $rules): string
            {
                return '';
            }
        };
    }

    public function testGetName(): void
    {
        $this->assertSame('Bob', $this->player->getName());
    }
}

