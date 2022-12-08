<?php

namespace Aoc\Aoc2022;

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
        return $this->detect(4);
    }

    protected function runPart2()
    {
        return $this->detect(14);
    }

    protected function detect(int $count)
    {
        $data = str_split($this->lines[0]);
        $lastChars = [];
        foreach($data as $i => $char)
        {
            if(count($lastChars) == $count){
                array_shift($lastChars);
            }
            $lastChars[] = $char;

            if(count($lastChars) == $count){
                if(count(array_unique($lastChars)) == $count){
                    return $i + 1;
                }
            }
        }
        
        return 0;
    }
}
