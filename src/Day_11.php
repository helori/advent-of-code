<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_11 extends Aoc
{
    protected $matrix = null;
    protected $rows = null;
    protected $cols = null;

    protected function init()
    {
        $this->matrix = array_filter($this->lines);
        foreach($this->matrix as $i => $line){
            $this->matrix[$i] = $this->toInts(str_split(trim($line)));
        }
        $this->rows = $this->matrixRows($this->matrix);
        $this->cols = $this->matrixCols($this->matrix);
    }

    protected function runPart1()
    {
        $steps = 100;
        $flashes = 0;

        for($step=0; $step<$steps; ++$step)
        {
            $hasFlashed = [];

            for($r=0; $r<$this->rows; ++$r)
            {
                for($c=0; $c<$this->cols; ++$c)
                {
                    $this->tryInc($r, $c, $hasFlashed);
                }
            }

            $flashes += count($hasFlashed);
            
            /*echo "---------\n";
            echo "After step ".($step + 1)." :\n\n";
            $this->renderMatrix($this->matrix);*/
        }

        return $flashes;
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

    protected function runPart2()
    {
        $this->init();
        $steps = 1000000;
        $allStep = null;

        for($step=0; $step<$steps; ++$step)
        {
            $hasFlashed = [];

            for($r=0; $r<$this->rows; ++$r)
            {
                for($c=0; $c<$this->cols; ++$c)
                {
                    $this->tryInc($r, $c, $hasFlashed);
                }
            }

            $total = 0;
            $this->readMatrix($this->matrix, function($value) use(&$total) {
                $total += $value;
            });
            
            echo "---------\n";
            echo "After step ".($step + 1)." :\n\n";
            $this->renderMatrix($this->matrix);
            echo "Total : ".$total."\n";

            if($total === 0){
                $allStep = ($step + 1);
                break;
            }
        }

        return $allStep;
    }
}
