<?php

namespace Aoc\Aoc2019;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_02 extends Aoc
{
    protected array $values;

    protected function init()
    {
        $this->values = array_map(function($v){ 
            return intVal($v); 
        }, explode(',', $this->lines[0]));
    }

    protected function runPart1()
    {
       return $this->program(12, 2);
    }

    protected function runPart2()
    {
        $expected = 19690720;
        $result = null;

        for($n=0; $n<100; $n++)
        {
            for($v=0; $v<100; $v++)
            {
                $result = $this->program($n, $v);
                if($result === $expected){
                    break;
                }
            }
            if($result === $expected){
                break;
            }
        }
        
        return 100 * $n + $v;
    }

    protected function program(int $noun, int $verb)
    {
        $v = $this->values;

        $v[1] = $noun;
        $v[2] = $verb;

        for($i=0; $i<count($v)-3; $i+=4)
        {
            if($v[$i] === 1)
            {
                $v[$v[$i+3]] = $v[$v[$i+1]] + $v[$v[$i+2]];
            }
            else if($v[$i] === 2)
            {
                $v[$v[$i+3]] = $v[$v[$i+1]] * $v[$v[$i+2]];
            }
            else if($v[$i] === 99)
            {
                break;
            }
        }
        return $v[0];
    }
}
