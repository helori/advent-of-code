<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_03 extends Aoc
{
    protected $trimLines = false;
    protected $memory = '';

    protected function init()
    {
        $this->memory = '';
        foreach($this->lines as $line)
        {
            $this->memory .= $line;
        }
    }

    protected function runPart1()
    {
        return $this->resultFor($this->memory);
    }

    protected function runPart2()
    {
        $reduced = preg_replace('/don\'t\(\)(.*?)do\(\)/s', '', $this->memory);
        $reduced = preg_replace('/don\'t\(\)(.*?)$/s', '', $reduced);
        return $this->resultFor($reduced);
    }

    protected function resultFor($line)
    {
        $matches = null;
        preg_match_all('/mul\(([0-9]{1,3}),([0-9]{1,3})\)/s', $line, $matches);
        
        $vals1 = $matches[1];
        $vals2 = $matches[2];

        $result = 0;
        
        for($i=0; $i<count($vals1); $i++)
        {
            $val1 = intVal($vals1[$i]);
            $val2 = intVal($vals2[$i]);
            $result += ($val1 * $val2);
        }

        return $result;
    }
}
