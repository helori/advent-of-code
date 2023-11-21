<?php

namespace Aoc\Aoc2022;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_07 extends Aoc
{
    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $dirs = [];

        $currentDir = [
            'name' => '/',
            'size' => 0,
        ];

        foreach($this->lines as $line)
        {
            // Leave directory
            if($line === '$ cd ..')
            {

            }
        }

        return 0;
    }

    protected function readDir($name, $cmdIdx)
    {
        $size = 0;
        $depth = 0;

        while($depth >= 0)
        {

        }

        return [
            'name' => $name,
            'size' => $size,
        ];
    }

    protected function runPart2()
    {
        return 0;
    }
}
