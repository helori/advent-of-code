<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_01 extends Aoc
{
    protected function init()
    {
        $this->lines = array_filter($this->lines);
        $this->lines = $this->toInts($this->lines);
    }

    protected function runPart1()
    {
        $values = $this->lines;
        $inc = 0;
        foreach($values as $i => $value)
        {
            if($i > 0){
                $inc += ($value > $values[$i - 1]) ? 1 : 0;
            }
        }
        return $inc;
    }

    protected function runPart2()
    {
        $values = $this->lines;
        $inc = 0;
        foreach($values as $i => $value)
        {
            if($i >= 3){
                $sum1 = $values[$i - 3] + $values[$i - 2] + $values[$i - 1];
                $sum2 = $values[$i - 2] + $values[$i - 1] + $values[$i - 0];
                if($sum2 > $sum1){
                    $inc++;
                }
            }
        }
        return $inc;
    }
}
