<?php declare(strict_types=1);

namespace Tests\MyGame\Rules;

use MyGame\Rules\Exception\InvalidGestureException;
use MyGame\Rules\RockPaperScissors;
use PHPUnit\Framework\TestCase;

class RockPaperScissorsTest extends TestCase
{
    private RockPaperScissors $rules;

    public function setUp(): void
    {
        $this->rules = new RockPaperScissors();
    }

    public function testGetGestures(): void
    {
        $this->assertEquals(['R', 'P', 'S'], $this->rules->getGestures());
    }

    /**
     * @dataProvider providerCheckGesture
     *
     * @param string $gesture
     * @param bool   $result
     */
    public function testCheckGesture(string $gesture, bool $result): void
    {
        $this->assertSame($result, $this->rules->checkGesture($gesture));
    }

    public function providerCheckGesture(): array
    {
        return [
            ['R', true],
            ['r', false],
            ['P', true],
            ['p', false],
            ['S', true],
            ['s', false],
            ['a', false],
            ['A', false],
        ];
    }

    /**
     * @dataProvider providerCompare
     *
     * @param string $gesture1
     * @param string $gesture2
     * @param int    $expected
     */
    public function testCompare(string $gesture1, string $gesture2, int $expected): void
    {
        $this->assertSame($expected, $this->rules->compare($gesture1, $gesture2));
    }

    public function providerCompare(): array
    {
        return [
            [RockPaperScissors::ROCK, RockPaperScissors::ROCK, 0],
            [RockPaperScissors::ROCK, RockPaperScissors::PAPER, -1],
            [RockPaperScissors::ROCK, RockPaperScissors::SCISSORS, 1],
            [RockPaperScissors::PAPER, RockPaperScissors::ROCK, 1],
            [RockPaperScissors::PAPER, RockPaperScissors::PAPER, 0],
            [RockPaperScissors::PAPER, RockPaperScissors::SCISSORS, -1],
            [RockPaperScissors::SCISSORS, RockPaperScissors::ROCK, -1],
            [RockPaperScissors::SCISSORS, RockPaperScissors::PAPER, 1],
            [RockPaperScissors::SCISSORS, RockPaperScissors::SCISSORS, 0],
        ];
    }

    /**
     * @dataProvider providerCompareWithInvalidGesture
     *
     */
    public function testCompareWithInvalidGesture(string $gesture1, string $gesture2): void
    {
        $this->expectException(InvalidGestureException::class);
        $this->rules->compare($gesture1, $gesture2);
    }

    public function providerCompareWithInvalidGesture(): array
    {
        return [
            ['a', RockPaperScissors::ROCK],
            [RockPaperScissors::PAPER, 'a'],
            ['a', 'a'],
        ];
    }
}

