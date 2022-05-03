<?php

declare(strict_types=1);

namespace Tests\MyGame;

use MyGame\Game;
use MyGame\Player\Computer;
use MyGame\Player\Human;
use MyGame\Player\IPlayer;
use MyGame\Rules\RockPaperScissors;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class GameWithRockPaperScissorsTest extends TestCase
{
    private RockPaperScissors $rules;

    public function setUp(): void
    {
        $this->rules = new RockPaperScissors();
    }

    private function createPlayerStubWithMoves(array $playerMoves): IPlayer
    {
        $player = $this->createStub(Human::class);
        $player->method('playOneMove')
            ->will($this->onConsecutiveCalls(...$playerMoves));

        return $player;
    }

    public function testWinnerNoWinner(): void
    {
        $rules = new RockPaperScissors();

        $player1 = $this->createStub(Human::class);
        $player2 = $this->createStub(Computer::class);
        $game = new Game($rules, $player1, $player2);

        $this->expectException(RuntimeException::class);
        $game->winner();
    }

    /**
     * @dataProvider providerMoves
     */
    public function testPlayPlayer1Wins(int $turns, array $player1Moves, array $player2Moves): void
    {
        $player1 = $this->createPlayerStubWithMoves($player1Moves);
        $player2 = $this->createPlayerStubWithMoves($player2Moves);

        $game = new Game($this->rules, $player1, $player2);
        // Suppress output to console
        $this->setOutputCallback(function () {
        });
        $game->play($turns);
        $this->assertSame($player1, $game->winner());
    }

    /**
     * @dataProvider providerMoves
     */
    public function testPlayPlayer2Wins(int $turns, array $player2Moves, array $player1Moves): void
    {
        $rules = new RockPaperScissors();

        $player1 = $this->createPlayerStubWithMoves($player1Moves);
        $player2 = $this->createPlayerStubWithMoves($player2Moves);

        $game = new Game($this->rules, $player1, $player2);
        // Suppress output to console
        $this->setOutputCallback(function () {
        });
        $game->play($turns);
        $this->assertSame($player2, $game->winner());
    }

    public function providerMoves(): array
    {
        return [
            [
                1,
                [RockPaperScissors::ROCK],
                [RockPaperScissors::SCISSORS],
            ],
            [
                3,
                [RockPaperScissors::ROCK, RockPaperScissors::PAPER, RockPaperScissors::SCISSORS],
                [RockPaperScissors::SCISSORS, RockPaperScissors::ROCK, RockPaperScissors::PAPER],
            ],
            [
                3,
                [RockPaperScissors::ROCK, RockPaperScissors::ROCK, RockPaperScissors::ROCK, RockPaperScissors::PAPER],
                [RockPaperScissors::ROCK, RockPaperScissors::ROCK, RockPaperScissors::ROCK, RockPaperScissors::ROCK],
            ],
            [
                3,
                [RockPaperScissors::PAPER, RockPaperScissors::ROCK, RockPaperScissors::PAPER, RockPaperScissors::ROCK, RockPaperScissors::PAPER],
                [RockPaperScissors::ROCK, RockPaperScissors::PAPER, RockPaperScissors::PAPER, RockPaperScissors::ROCK, RockPaperScissors::ROCK],
            ],
        ];
    }
}

