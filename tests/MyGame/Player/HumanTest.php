<?php

declare(strict_types=1);

namespace Tests\MyGame\Player;

use MyGame\Player\Human;
use MyGame\Rules\IRules;
use MyGame\Rules\RockPaperScissors;
use PHPUnit\Framework\TestCase;

class HumanTest extends TestCase
{
    /**
     * Test naïf
     * FIXME test avec RockPaperScissors et donc malheureusement dépendant de RockPaperScissors
     */
    public function testPlayOneMove(): void
    {
        $mockHuman = $this->createPartialMock(Human::class, ['getInput']);
        $mockHuman->expects($this->once())
            ->method('getInput')
            ->willReturn('R');

        $this->expectOutputString('Saisissez votre geste (R, P, S) : ');
        $this->assertSame('R', $mockHuman->playOneMove(new RockPaperScissors()));
    }

    /**
     * Test naïf
     * FIXME test avec RockPaperScissors et donc malheureusement dépendant de RockPaperScissors
     */
    public function testPlayOneMoveWithIncorrectInputs(): void
    {
        $mockHuman = $this->createPartialMock(Human::class, ['getInput']);

        $mockHuman->expects($this->exactly(3))
            ->method('getInput')
            ->will($this->onConsecutiveCalls('a', 'b', 'R'));
        $message = 'Saisissez votre geste (R, P, S) : ';
        $this->expectOutputString($message . $message . $message);
        $this->assertSame('R', $mockHuman->playOneMove(new RockPaperScissors()));
    }

    /*
     **************************************************************************************************************
     * Méthodologie de test plus rigoureuse, indépendante de classes de règles réelles, mais un peu plus complexe *
     **************************************************************************************************************
     */

    /**
     * Objet factice basé sur IRules
     * @var IRules|\PHPUnit\Framework\MockObject\MockObject
     */
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
    }

    public function testPlayOneMoveWithFakeRules(): void
    {
        $mockHuman = $this->createPartialMock(Human::class, ['getInput']);
        $mockHuman->expects($this->once())
            ->method('getInput')
            ->willReturn('B');

        $this->expectOutputString('Saisissez votre geste (A, B, C) : ');
        $this->assertSame('B', $mockHuman->playOneMove($this->rules));
    }

    public function testPlayOneMoveWithFakeRulesAndIncorrectInputs(): void
    {
        $mockHuman = $this->createPartialMock(Human::class, ['getInput']);

        $mockHuman->expects($this->exactly(3))
            ->method('getInput')
            ->will($this->onConsecutiveCalls('F', 'P', 'C'));
        $message = 'Saisissez votre geste (A, B, C) : ';
        $this->expectOutputString($message . $message . $message);
        $this->assertSame('C', $mockHuman->playOneMove($this->rules));
    }
}

