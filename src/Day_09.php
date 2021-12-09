<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_09 extends Aoc
{
    protected $matrix = null;
    protected $rows = null;
    protected $cols = null;

    protected $lowPoints = [];

    protected $basins = [];
    protected $currentBasin = 0;
    protected $checkedPoints = [];

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
        $this->readMatrix($this->matrix, function($v, $r, $c)
        {
            $isLow = true;
            if($r > 0){
                $isLow &= ($v < $this->matrix[$r - 1][$c]);
            }
            if($r < $this->rows - 1){
                $isLow &= ($v < $this->matrix[$r + 1][$c]);
            }
            if($c < $this->cols - 1){
                $isLow &= ($v < $this->matrix[$r][$c + 1]);
            }
            if($c > 0){
                $isLow &= ($v < $this->matrix[$r][$c - 1]);
            }
            
            if($isLow){
                $this->lowPoints[] = $v;
            }
        });

        $this->lowPoints = $this->increment($this->lowPoints, 1);

        return array_sum($this->lowPoints);
    }

    protected function runPart2()
    {
        $this->readMatrix($this->matrix, function($value, $r, $c) {
            $inNewBasin = $this->checkPoint($r, $c);
            if($inNewBasin){
                $this->basins[] = $this->currentBasin;
                $this->currentBasin = 0;
            }
        });

        $b = $this->basins;
        rsort($b);

        return $b[0] * $b[1] * $b[2];
    }

    protected function checkPoint($r, $c)
    {
        $checkKey = $r.'_'.$c;
        if(!isset($this->checkedPoints[$checkKey]))
        {
            $this->checkedPoints[$checkKey] = true;
            if($this->matrix[$r][$c] < 9){
                ++$this->currentBasin;
                $this->checkAround($r, $c, $this->rows, $this->cols);
                return true;
            }
        }
        return false;
    }

    protected function checkAround($r, $c, $rows, $cols)
    {
        if($r > 0){
            $this->checkPoint($r - 1, $c, $rows, $cols);
        }
        if($r < $rows - 1){
            $this->checkPoint($r + 1, $c, $rows, $cols);
        }
        if($c < $cols - 1){
            $this->checkPoint($r, $c + 1, $rows, $cols);
        }
        if($c > 0){
            $this->checkPoint($r, $c - 1, $rows, $cols);
        }
    }
}
