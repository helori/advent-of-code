<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_08 extends Aoc
{
    protected $dirs = [];
    protected $map = [];
    protected $dirsCount = 0;

    protected function init()
    {
        $this->dirs = array_map(function($v){
            return ($v === 'L') ? 0 : 1;
        }, str_split($this->lines[0]));
        
        $this->dirsCount = count($this->dirs);

        $this->map = [];
        for($i=2; $i<count($this->lines); ++$i)
        {
            $key = trim(Str::before($this->lines[$i], '='));
            $left = trim(Str::before(Str::after($this->lines[$i], '('), ','));
            $right = trim(Str::before(Str::after($this->lines[$i], ','), ')'));

            $this->map[$key] = [$left, $right];
        }
    }

    protected function runPart1()
    {
        $pos = 'AAA';
        $step = 0;
        
        while($pos !== 'ZZZ')
        {
            $idx = $step % $this->dirsCount;
            $pos = $this->map[$pos][$this->dirs[$idx]];
            ++$step;
        }

        return $step;
    }

    protected function runPart2()
    {
        $values = array_filter(array_keys($this->map), function($v) {
            return Str::endsWith($v, 'A');
        });
        
        $zPos = [];
        
        foreach($values as $value)
        {
            $zPos[] = $this->stepsToNextZ($value);
        }

        $lcm = $zPos[0];
        for($i=1; $i<count($zPos); $i++)
        {
            $lcm = ($lcm * $zPos[$i]) / gmp_gcd($lcm, $zPos[$i]);
        }

        return $lcm;
    }

    protected function stepsToNextZ($valueStart)
    {
        $v = $valueStart;
        $step = 0;
        $end = false;

        while(!$end)
        {
            $idx = $step % $this->dirsCount;
            $v = $this->map[$v][$this->dirs[$idx]];
            $end = (substr($v, 2, 1) === 'Z');
            ++$step;
        }

        return $step;
    }
}
