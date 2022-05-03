<?php
declare(strict_types=1);

namespace MyGame\Rules;

interface IRules
{
    /**
     * @return string[]
     */
    public function getGestures(): array;

    public function compare(string $gesture1, string $gesture2): int;

    public function checkGesture(string $gesture): bool;
}

