<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_06 extends Aoc
{
    protected $cycles = null;

    protected function init()
    {
        $values = explode(',', $this->lines[0]);
        $values = $this->toInts($values);

        $cycles = array_fill(0, 9, 0);
        foreach($values as $i => &$value){
            $cycles[$value] += 1;
        }

        $this->cycles = $cycles;
    }

    protected function runPart1()
    {
        $this->processCycles(80);
        return array_sum($this->cycles);
    }

    protected function runPart2()
    {
        $this->processCycles(256);
        return array_sum($this->cycles);
    }
    
    protected function processCycles($days)
    {
        for($d=0; $d<$days; ++$d)
        {
            $newBorn = $this->cycles[0];
            for($c=0; $c<=7; ++$c)
            {
                $this->cycles[$c] = $this->cycles[$c+1];
            }
            $this->cycles[8] = $newBorn;
            $this->cycles[6] += $newBorn;
        }
    }
}
