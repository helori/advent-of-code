<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_07 extends Aoc
{
    protected $values = null;

    protected function init()
    {
        $values = explode(',', $this->lines[0]);
        $this->values = $this->toInts($values);
    }

    protected function runPart1()
    {
        $middle = collect($this->values)->median();

        $m1 = intVal(round($middle, PHP_ROUND_HALF_UP));
        $m2 = intVal(round($middle, PHP_ROUND_HALF_DOWN));
        
        $fuel1 = $this->fuelConsumption1($m1);
        $fuel2 = $this->fuelConsumption1($m2);
        
        return min($fuel1, $fuel2);
    }

    protected function fuelConsumption1($m)
    {
        return array_sum(array_map(function($v) use($m) { 
            return abs($v - $m);
        }, $this->values));
    }

    protected function runPart2()
    {
        $middle = collect($this->values)->avg();

        $m1 = intVal(round($middle, PHP_ROUND_HALF_UP));
        $m2 = intVal(round($middle, PHP_ROUND_HALF_DOWN));
        
        $fuel1 = $this->fuelConsumption2($m1);
        $fuel2 = $this->fuelConsumption2($m2);
        
        return min($fuel1, $fuel2);
    }

    protected function fuelConsumption2($m)
    {
        return array_sum(array_map(function($v) use($m) { 
            return abs($v - $m) * (abs($v - $m)+1) / 2; 
        }, $this->values));
    }
}
