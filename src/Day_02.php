<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_02 extends Aoc
{
    protected function init()
    {
        $this->lines = array_filter($this->lines);
    }

    protected function runPart1()
    {
        $values = $this->lines;

        $h = 0;
        $d = 0;

        foreach($values as $i => $value)
        {
            $parts = explode(' ', $value);
            $mvt = trim($parts[0]);
            $val = intVal(trim($parts[1]));

            if($mvt === 'forward'){
                $h += $val;
            }else if($mvt === 'down'){
                $d += $val;
            }else if($mvt === 'up'){
                $d -= $val;
            }
        }
        return $h * $d;
    }

    protected function runPart2()
    {
        $values = $this->lines;

        $h = 0;
        $a = 0;
        $d = 0;

        foreach($values as $i => $value)
        {
            $parts = explode(' ', $value);
            $mvt = trim($parts[0]);
            $val = intVal(trim($parts[1]));

            if($mvt === 'forward'){
                $h += $val;
                $d += ($a * $val);
            }else if($mvt === 'down'){
                $a += $val;
            }else if($mvt === 'up'){
                $a -= $val;
            }
        }
        return $h * $d;
    }
}
