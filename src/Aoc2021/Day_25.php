<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_25 extends Aoc
{
    protected $matrix;

    protected function init()
    {
        $this->lines = array_filter($this->lines);
        $this->matrix = array_map(function($line){
            return str_split($line);
        }, $this->lines);
    }

    protected function runPart1()
    {
        $canMove = true;
        $step = 0;

        while($canMove)
        {
            //dump("STEP $step");
            //$this->renderMatrix($this->matrix);

            $step++;
            $canMove = $this->step();
        }
        
        return $step;
    }

    protected function step()
    {
        $rows = $this->matrixNumRows($this->matrix);
        $cols = $this->matrixNumCols($this->matrix);

        $canMove = false;
        
        $newMatrix = [];
        for($r=0; $r<$rows; ++$r)
        {
            $newMatrix[] = [];
        }

        for($r=0; $r<$rows; ++$r)
        {
            for($c=0; $c<$cols; ++$c)
            {
                $val = $this->matrix[$r][$c];
                $canMoveVal = false;
                if($val === '>')
                {
                    $nextC = ($c === $cols-1) ? 0 : $c + 1;
                    $nextVal = $this->matrix[$r][$nextC];
                    if($nextVal === '.')
                    {
                        $canMoveVal = true;
                        $newMatrix[$r][$c] = '.';
                        $newMatrix[$r][$nextC] = '>';
                        $c++;
                    }
                }
                if(!$canMoveVal)
                {
                    $newMatrix[$r][$c] = $val;
                }
                $canMove |= $canMoveVal;
            }
        }
        $this->matrix = $newMatrix;



        
        $this->matrix = $this->matrixCols($this->matrix);

        $rows = $this->matrixNumRows($this->matrix);
        $cols = $this->matrixNumCols($this->matrix);

        $newMatrix = [];
        for($r=0; $r<$rows; ++$r)
        {
            $newMatrix[] = [];
        }

        for($r=0; $r<$rows; ++$r)
        {
            for($c=0; $c<$cols; ++$c)
            {
                $val = $this->matrix[$r][$c];
                $canMoveVal = false;
                if($val === 'v')
                {
                    $nextC = ($c === $cols-1) ? 0 : $c + 1;
                    $nextVal = $this->matrix[$r][$nextC];
                    if($nextVal === '.')
                    {
                        $canMoveVal = true;
                        $newMatrix[$r][$c] = '.';
                        $newMatrix[$r][$nextC] = 'v';
                        $c++;
                    }
                }
                if(!$canMoveVal)
                {
                    $newMatrix[$r][$c] = $val;
                }
                $canMove |= $canMoveVal;
            }
        }
        $this->matrix = $this->matrixCols($newMatrix);

        
        return $canMove;
    }

    protected function runPart2()
    {
        return 0;
    }
}
