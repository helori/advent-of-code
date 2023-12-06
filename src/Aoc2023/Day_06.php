<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_06 extends Aoc
{
    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $times = $this->toInts(explode(' ', $this->singleWhitespaces(trim(Str::after($this->lines[0], ':')))));
        $distances = $this->toInts(explode(' ', $this->singleWhitespaces(trim(Str::after($this->lines[1], ':')))));
        $results = [];

        for($i=0; $i<count($times); ++$i)
        {
            $tMax = $times[$i];
            $dMax = $distances[$i];

            $wins = $this->getWins($tMax, $dMax);
            $results[] = count($wins);
        }

        return array_product($results);
    }

    protected function runPart2()
    {
        $time = intVal($this->removeWhitespaces(trim(Str::after($this->lines[0], ':'))));
        $distance = intVal($this->removeWhitespaces(trim(Str::after($this->lines[1], ':'))));

        $wins = $this->getWins($time, $distance);

        return count($wins);
    }

    protected function getWins($tMax, $dMax)
    {
        $wins = [];
        for($t=0; $t<=$tMax; ++$t)
        {
            $speed = $t;
            $d = ($tMax - $t) * $speed;
            if($d > $dMax){
                $wins[] = $t;
            }
        }
        return $wins;
    }
}
