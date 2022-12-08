<?php

namespace Aoc\Aoc2020;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_05 extends Aoc
{
    protected function init()
    {
        $this->lines = array_filter($this->lines);
    }

    protected function runPart1()
    {
        $max = 0;
        foreach($this->lines as $line)
        {
            $id = $this->getSeatId($line);
            $max = max($max, $id);
        }

        return $max;
    }

    protected function runPart2()
    {
        $rows = 128;
        $cols = 8;
        $ids = [];

        for($r = 0; $r < $rows; $r++){
            for($c = 0; $c < $cols; $c++){
                $ids[] = 8 * $r + $c;
            }
        }

        $pass = [];
        foreach($this->lines as $line)
        {
            $pass[] = $this->getSeatId($line);
        }

        $diff = array_values(array_diff($ids, $pass));

        $seatId = null;
        foreach($diff as $id){
            if(!in_array($id+1, $diff) && !in_array($id-1, $diff)){
                $seatId = $id;
            }
        }

        return $seatId;
    }

    protected function getSeatId($line)
    {
        $row = $this->process(substr($line, 0, 7), 0, 127);
        $col = $this->process(substr($line, 7, 3), 0, 7);

        return 8 * $row + $col;
    }
    
    protected function process($sequence, $min, $max)
    {
        $letters = str_split($sequence, 1);
        $num = $min;
        $range = ($max + 1 - $min);

        foreach($letters as $letter)
        {
            if($letter == 'B' || $letter == 'R')
            {
                $num += $range / 2;
            }
            $range /= 2;
        }
        return $num;
    }
}
