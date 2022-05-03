<?php

declare(strict_types=1);

namespace Tests\MyGame\Player;

use MyGame\Player\Computer;
use MyGame\Rules\IRules;
use MyGame\Rules\RockPaperScissors;
use PHPUnit\Framework\TestCase;

class ComputerTest extends TestCase
{
    /**
     * Test naïf
     * FIXME test avec RockPaperScissors et donc malheureusement dépendant de RockPaperScissors
     */
    public function testPlayOneMove(): void
    {
        $rules = new RockPaperScissors();
        $computer = new Computer('HAL');

        for ($i = 0; $i < 300; ++$i) {
            $this->assertTrue($rules->checkGesture($computer->playOneMove($rules)));
        }
    }


    /*
     **************************************************************************************************************
     * Méthodologie de test plus rigoureuse, indépendante de classes de règles réelles, mais un peu plus complexe *
     **************************************************************************************************************
     */

    /**
     * Vérifier que les gestes tirés au sort sont valides
     */
    public function testPlayOneMoveWithFakeRules(): void
    {
        /**
         * @var string[] Les gestes factices
         */
        $gestures = ['A', 'B', 'C'];

        // Création de l'objet factice basé sur IRules
        $rules = $this->createMock(IRules::class);

        // Configuration de la méthode getGestures
        $rules->expects($this->any())
            ->method('getGestures')
            ->willReturn($gestures);

        $computer = new Computer('HAL');

        for ($i = 0; $i < 300; ++$i) {
            $this->assertContains($computer->playOneMove($rules), $gestures);
        }
    }

    /**
     * Vérifier que les gestes tirés au sort sont valides et que tous les gestes sont présentes dans le tirage
     */
    public function testPlayOneMoveWithFakeRulesAndCheckThatAllGesturesAreDrawn(): void
    {
        /**
         * @var string[] les gestes factices
         */
        $gestures = ['A', 'B', 'C'];

        // Création de l'objet factice basé sur IRules
        $rules = $this->createMock(IRules::class);

        // Configuration de la méthode getGestures
        $rules->expects($this->any())
            ->method('getGestures')
            ->willReturn($gestures);

        $computer = new Computer('HAL');

        // Décompte des gestes tirés aléatoirement
        $gestureCount = ['A' => 0, 'B' => 0, 'C' => 0];

        for ($i = 0; $i < 300; ++$i) {
            $randomGesture = $computer->playOneMove($rules);
            $this->assertContains($randomGesture, $gestures);
            // Comptage des gestes tirés
            $gestureCount[$randomGesture]++;
        }

        // Vérifier que tous les gestes ont été tirés de façon à peu près équitable
        foreach ($gestureCount as $gesture => $count) {
            $this->assertGreaterThan(70, $count, "All gestures should be drawn");
        }
    }
}

