<?php

declare(strict_types=1);

namespace Tests\MyGame;

use MyGame\Game;
use MyGame\Player\Computer;
use MyGame\Player\Human;
use MyGame\Player\IPlayer;
use MyGame\Rules\IRules;
use MyGame\Rules\RockPaperScissors;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class GameTest extends TestCase
{
    private IRules $rules;

    /**
     * Création de la fixture basée sur IRules
     */
    protected function setUp(): void
    {
        // Création de l'objet factice
        $this->rules = $this->createMock(IRules::class);
        // Configuration de la méthode getGestures
        $this->rules->expects($this->any())
            ->method('getGestures')
            ->willReturn(['A', 'B', 'C']);
        // Configuration de la méthode checkGestures
        $this->rules->expects($this->any())
            ->method('checkGesture')
            ->willReturnMap(
                [
                    ['A', true],
                    ['B', true],
                    ['C', true],
                    ['F', false],
                    ['P', false],
                ]
            );
        // Configuration de la méthode compare
        $this->rules->expects($this->any())
            ->method('compare')
            ->willReturnMap(
                [
                    ['A', 'A', 0],
                    ['A', 'B', 1],
                    ['A', 'C', -1],
                    ['B', 'A', -1],
                    ['B', 'B', 0],
                    ['B', 'C', 1],
                    ['C', 'A', 1],
                    ['C', 'B', -1],
                    ['C', 'C', 0],
                ]
            );
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
        $player1 = $this->createStub(Human::class);
        $player2 = $this->createStub(Computer::class);
        $game = new Game($this->rules, $player1, $player2);

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
                ['A'],
                ['B'],
            ],
            [
                3,
                ['A', 'B', 'C'],
                ['B', 'C', 'A'],
            ],
            [
                3,
                ['A', 'A', 'A', 'A'],
                ['A', 'A', 'A', 'B'],
            ],
            [
                3,
                ['A', 'A', 'A', 'A', 'A'],
                ['C', 'B', 'A', 'A', 'B'],
            ],
        ];
    }
}

