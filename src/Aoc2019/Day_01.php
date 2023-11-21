<?php

namespace Aoc\Aoc2019;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_01 extends Aoc
{
    protected function init()
    {
        $this->lines = array_filter($this->lines);
    }

    protected function runPart1()
    {
        $fuelTotal = 0;
        foreach($this->lines as $line)
        {
            $fuel = floor(intVal($line) / 3) - 2;
            $fuelTotal += $fuel;
        }
        return intVal($fuelTotal);
    }

    protected function runPart2()
    {
        $fuelTotal = 0;
        foreach($this->lines as $line)
        {
            $mass = intVal($line);
            while($mass > 0)
            {
                $fuel = max(0, floor($mass / 3) - 2);
                $mass = $fuel;
                $fuelTotal += $fuel;
            }
        }
        return intVal($fuelTotal);
    }
}
