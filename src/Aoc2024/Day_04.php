<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_04 extends Aoc
{
    protected function init()
    {
        
    }

    protected function runPart1()
    {
        return $this->runPart1Sol1();

        /*$matrix = $this->fileMatrix(false, '');
        $numRows = $this->matrixNumRows($matrix);
        $numCols = $this->matrixNumCols($matrix);

        $counts = 0;
        $length = 4; // On parcourt la matrice par carrés de côté 4 (longueur de XMAS)

        for($r=0; $r<$numRows - ($length - 1); ++$r)
        {
            for($c=0; $c<$numCols - ($length - 1); ++$c)
            {
                $submatrix = [];
                for($i=0; $i<$length; ++$i){
                    $line = [];
                    for($j=0; $j<$length; ++$j){
                        $line[] = $matrix[$r+$i][$c+$j];
                    }
                    $submatrix[] = $line;
                }
            }
        }
        return $counts;*/
    }

    protected function runPart2()
    {
        $matrix = $this->fileMatrix(false, '');
        $numRows = $this->matrixNumRows($matrix);
        $numCols = $this->matrixNumCols($matrix);

        $counts = 0;

        for($r=0; $r<$numRows - 2; ++$r)
        {
            for($c=0; $c<$numCols - 2; ++$c)
            {
                $diag1 = join([ $matrix[$r][$c], $matrix[$r+1][$c+1], $matrix[$r+2][$c+2] ]);
                $diag2 = join([ $matrix[$r+2][$c], $matrix[$r+1][$c+1], $matrix[$r][$c+2] ]);

                if(($diag1 === 'MAS' || strrev($diag1) === 'MAS') && ($diag2 === 'MAS' || strrev($diag2) === 'MAS'))
                {
                    ++$counts;
                }

                /*$submatrix = [
                    [ $matrix[$r][$c], $matrix[$r][$c+1], $matrix[$r][$c+2] ],
                    [ $matrix[$r+1][$c], $matrix[$r+1][$c+1], $matrix[$r+1][$c+2] ],
                    [ $matrix[$r+2][$c], $matrix[$r+2][$c+1], $matrix[$r+2][$c+2] ],
                ];*/
            }
        }
        return $counts;
    }

    protected function runPart1Sol1()
    {
        $matrix = $this->fileMatrix(false, '');
        
        $rows = $matrix;
        $cols = $this->matrixCols($matrix);
        $numRows = $this->matrixNumRows($matrix);
        $numCols = $this->matrixNumCols($matrix);
        $diags = [];

        for($i=0; $i<$numRows; ++$i)
        {
            $diag1 = [];
            $diag2 = [];
            $diag3 = [];
            $diag4 = [];
            
            for($j=0; $j<$numCols; ++$j)
            {
                if($i+$j < $numRows)
                {
                    $diag1[] = $matrix[$i + $j][$j];
                    $diag2[] = $matrix[$numRows - 1 - ($i + $j)][$j];
                    
                }
                if($i+$j+1 < $numRows)
                {
                    $diag3[] = $matrix[$j][$i+$j+1];
                    $diag4[] = $matrix[$numRows - 1 - $j][$i+$j+1];
                }
            }
            $diags[] = $diag1;
            $diags[] = $diag2;
            $diags[] = $diag3;
            $diags[] = $diag4;
        }

        $candidates = [];
        foreach($rows as $item)
        {
            $candidates[] = $item;
            $candidates[] = array_reverse($item);
        }
        foreach($cols as $item)
        {
            $candidates[] = $item;
            $candidates[] = array_reverse($item);
        }
        foreach($diags as $item)
        {
            $candidates[] = $item;
            $candidates[] = array_reverse($item);
        }

        $counts = 0;

        foreach($candidates as $candidate)
        {
            $counts += preg_match_all('/XMAS/', join($candidate));
        }

        return $counts;
    }
}
