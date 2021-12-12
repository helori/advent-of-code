<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_11 extends Aoc
{
    protected $matrix = null;

    protected function init()
    {
        $this->matrix = array_filter($this->lines);
        foreach($this->matrix as $i => $line){
            $this->matrix[$i] = $this->toInts(str_split(trim($line)));
        }
    }

    protected function runPart1()
    {
        $steps = 100;
        $flashes = 0;

        $this->runSteps($steps, function($step, $hasFlashed) use(&$flashes) {
            $flashes += count($hasFlashed);
        });

        return $flashes;
    }

    protected function runPart2()
    {
        $step = 0;
        $total = null;

        while($total !== 0)
        {
            $this->runStep();

            $step++;
            $total = 0;

            $this->readMatrix($this->matrix, function($value) use(&$total) {
                $total += $value;
            });
        }

        return $step;
    }

    protected function runSteps($steps, $callback)
    {
        for($step=0; $step<$steps; ++$step)
        {
            $hasFlashed = $this->runStep();

            /*echo "---------\n";
            echo "After step ".($step + 1)." :\n\n";
            $this->renderMatrix($this->matrix);*/

            $callback($step, $hasFlashed);
        }
    }

    protected function runStep()
    {
        $hasFlashed = [];
        $this->readMatrix($this->matrix, function($value, $r, $c) use(&$hasFlashed) {
            $this->tryInc($r, $c, $hasFlashed);
        });
        return $hasFlashed;
    }

    protected function tryInc($r, $c, &$hasFlashed)
    {
        $value = $this->matrixAt($this->matrix, $r, $c);
        if(!is_null($value))
        {
            $key = $r.'_'.$c;
            if(!isset($hasFlashed[$key]))
            {
                if($value === 9)
                {
                    $hasFlashed[$key] = [$r, $c];
                    $this->matrix[$r][$c] = 0;
                    $this->flashAround($r, $c, $hasFlashed);
                }
                else
                {
                    $this->matrix[$r][$c] += 1;
                }
            }
        }
    }

    protected function flashAround($r, $c, &$hasFlashed)
    {
        for($x = $r-1; $x <= $r+1; ++$x)
        {
            for($y = $c-1; $y <= $c+1; ++$y)
            {
                if(($x !== $r) || ($y !== $c))
                {
                    $this->tryInc($x, $y, $hasFlashed);
                }
            }
        }
    }
}
