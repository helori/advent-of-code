<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_01 extends Aoc
{
    protected $col1;
    protected $col2;

    protected function init()
    {
        $matrix = array_map(function($line){
            return array_values(array_filter(explode(' ', $line)));
        }, $this->lines);

        $col1 = $this->matrixCol($matrix, 0);
        $col2 = $this->matrixCol($matrix, 1);

        asort($col1);
        asort($col2);

        $this->col1 = array_values($col1);
        $this->col2 = array_values($col2);
    }

    protected function runPart1()
    {
        $diff = 0;
        for($i=0; $i<count($this->col1); ++$i)
        {
            $diff += abs($this->col1[$i] - $this->col2[$i]);
        }
        return $diff;
    }

    protected function runPart2()
    {
        $occurences = array_count_values($this->col2);
        $score = 0;
        for($i=0; $i<count($this->col1); ++$i)
        {
            if(isset($occurences[$this->col1[$i]])){
                $score += $this->col1[$i] * $occurences[$this->col1[$i]];
            }
        }
        return $score;
    }
}
