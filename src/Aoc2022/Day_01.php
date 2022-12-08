<?php

namespace Aoc\Aoc2022;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_01 extends Aoc
{
    protected function init()
    {
        $this->lines = $this->lines;
    }

    protected function runPart1()
    {
        $current = 0;
        $max = 0;
        foreach($this->lines as $line)
        {
            if($line){
                $current += intVal($line);
            }else{
                $max = max($max, $current);
                $current = 0;
            }
        }
        $max = max($max, $current);
        return $max;
    }

    protected function runPart2()
    {
        $current = 0;
        $values = [];
        
        foreach($this->lines as $line)
        {
            if($line){
                $current += intVal($line);
            }else{
                $values[] = $current;
                $current = 0;
            }
        }
        $values[] = $current;
        rsort($values, SORT_NUMERIC);
        return $values[0] + $values[1] + $values[2];
    }
}
