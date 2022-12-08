<?php

namespace Aoc\Aoc2022;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_04 extends Aoc
{
    protected function init()
    {
        $this->lines = array_filter($this->lines);
    }

    protected function runPart1()
    {
        $containedCount = 0;

        foreach($this->lines as $line)
        {
            $range1Min = intVal(Str::before($line, '-'));
            $range1Max = intVal(Str::before(Str::after($line, '-'), ','));
            $range2Min = intVal(Str::before(Str::after($line, ','), '-'));
            $range2Max = intVal(Str::afterLast($line, '-'));

            if(($range1Min >= $range2Min && $range1Max <= $range2Max) ||
                ($range2Min >= $range1Min && $range2Max <= $range1Max)){
                ++$containedCount;
            }
        }

        return $containedCount;
    }

    protected function runPart2()
    {
        $overlapCount = 0;

        foreach($this->lines as $line)
        {
            $range1Min = intVal(Str::before($line, '-'));
            $range1Max = intVal(Str::before(Str::after($line, '-'), ','));
            $range2Min = intVal(Str::before(Str::after($line, ','), '-'));
            $range2Max = intVal(Str::afterLast($line, '-'));

            if(($range1Max >= $range2Min && $range1Min <= $range2Max) ||
                ($range2Max >= $range1Min && $range2Min <= $range1Max)){
                ++$overlapCount;
            }
        }

        return $overlapCount;
    }
}
