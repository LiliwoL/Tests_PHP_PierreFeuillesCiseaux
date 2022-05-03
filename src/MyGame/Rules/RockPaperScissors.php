<?php

declare(strict_types=1);

namespace MyGame\Rules;

use MyGame\Rules\Exception\InvalidGestureException;
use MyGame\Rules\Gestures\RockPaperScissorsGestures;

class RockPaperScissors implements IRules
{
    public const PAPER = 'P';
    public const ROCK = 'R';
    public const SCISSORS = 'S';

    private const GESTURES = [self::ROCK, self::PAPER, self::SCISSORS];

    /**
     * @return string[]
     */
    public function getGestures(): array
    {
        return self::GESTURES;
    }

    public function compare(string $gesture1, string $gesture2): int
    {
        $k1 = $this->gestureIndex($gesture1);
        $k2 = $this->gestureIndex($gesture2);

        if ($k1 === $k2) {
            return 0;
        }

        if ((($k1 + 1) % count($this->getGestures())) == $k2) {
            return -1;
        }

        return 1;
    }

    public function checkGesture(string $gesture): bool
    {
        return in_array($gesture, $this->getGestures());
    }

    /**
     * @param string $gesture
     * @return int
     * @throws InvalidGestureException
     */
    protected function gestureIndex(string $gesture): int
    {
        $iGesture = array_search($gesture, $this->getGestures());
        if (false === $iGesture) {
            throw new InvalidGestureException("Invalid Gesture '{$gesture}'");
        }

        return $iGesture;
    }
}

