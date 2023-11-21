<?php

namespace Aoc\Aoc2022;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_08 extends Aoc
{
    protected function init()
    {
        
        
    }

    protected function runPart1()
    {
        $this->lines = array_filter($this->lines);
        $this->lines = array_map(function($line){
            return str_split($line);
        }, $this->lines);

        $rows = $this->matrixNumRows($this->lines);
        $cols = $this->matrixNumCols($this->lines);
        $trees = ($rows - 2) * 2 + ($cols * 2);


        foreach($this->lines as $line){
            $h = $line[0];
            for($i=1; $i<count($line); ++$i){
                if($h <= $h){
                    
                }
            }
        }
        
        unset($this->lines[0]);
        $this->lines = array_values($this->lines);
        unset($this->lines[count($this->lines) - 1]);
        $this->lines = array_values($this->lines);

        $this->lines = $this->matrixCols($this->lines);

        unset($this->lines[0]);
        $this->lines = array_values($this->lines);
        unset($this->lines[count($this->lines) - 1]);
        $this->lines = array_values($this->lines);

        $this->lines = $this->matrixCols($this->lines);
        
        $this->renderMatrix($this->lines);
        
        return 0;
    }

    protected function runPart2()
    {
        return 0;
    }
}
