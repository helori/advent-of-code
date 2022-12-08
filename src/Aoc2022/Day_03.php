<?php

namespace Aoc\Aoc2022;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_03 extends Aoc
{
    protected $priorities;

    protected function init()
    {
        $this->lines = array_filter($this->lines);

        $this->priorities = array_flip(array_values(array_merge(range('a', 'z'), range('A', 'Z'))));
        $this->priorities = array_map(function($v){return $v+1;}, $this->priorities);
    }

    protected function runPart1()
    {
        $sum = 0;

        foreach($this->lines as $line)
        {
            $comp1 = substr($line, 0, strlen($line) / 2);
            $comp2 = substr($line, strlen($line) / 2);

            $item = array_values(array_intersect(str_split($comp1), str_split($comp2)))[0];
            
            $sum += $this->priorities[$item];
        }

        return $sum;
    }

    protected function runPart2()
    {
        $sum = 0;

        $groups = array_chunk($this->lines, 3);

        foreach($groups as $group)
        {
            $item = array_values(array_intersect(str_split($group[0]), str_split($group[1]), str_split($group[2])))[0];
            $sum += $this->priorities[$item];
        }

        return $sum;
    }
}
