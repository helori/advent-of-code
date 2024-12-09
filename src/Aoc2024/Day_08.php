<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_08 extends Aoc
{
    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $matrix = $this->fileMatrix(false, '');
        $numRows = $this->matrixNumRows($matrix);
        $numCols = $this->matrixNumCols($matrix);

        $antennas = [];
        $this->readMatrix($matrix, function($v, $r, $c, $numRows, $numCols) use(&$antennas)
        {
            if($v !== '.')
            {
                if(!array_key_exists($v, $antennas)){
                    $antennas[$v] = [];
                }
                $antennas[$v][] = [$r, $c];
            }
        });

        $antinodes = [];
        foreach($antennas as $digit => $positions)
        {
            for($i=0; $i<count($positions); ++$i)
            {
                for($j=$i+1; $j<count($positions); ++$j)
                {
                    $r1 = $positions[$i][0];
                    $c1 = $positions[$i][1];
                    $r2 = $positions[$j][0];
                    $c2 = $positions[$j][1];
                    $dr = abs($r2 - $r1);
                    $dc = abs($c2 - $c1);

                    if($r1 < $r2){
                        if($c1 < $c2) { $a1 = [ $r1 - $dr, $c1 - $dc ]; $a2 = [ $r2 + $dr, $c2 + $dc ]; }
                        else  { $a1 = [ $r1 - $dr, $c1 + $dc ]; $a2 = [ $r2 + $dr, $c2 - $dc ]; }
                    }else{
                        if($c1 < $c2) { $a1 = [ $r2 - $dr, $c1 - $dc ]; $a2 = [ $r1 + $dr, $c2 + $dc ]; }
                        else  { $a1 = [ $r2 - $dr, $c1 + $dc ]; $a2 = [ $r1 + $dr, $c2 - $dc ]; }
                    }

                    $antinodes[implode('_', $a1)] = $a1;
                    $antinodes[implode('_', $a2)] = $a2;
                }
            }
        }

        foreach($antinodes as $key => $pos)
        {
            if(!($pos[0] >= 0 && $pos[0] < $numRows && $pos[1] >= 0 && $pos[1] < $numCols))
            {
                unset($antinodes[$key]);
            }
        }
        
        /*$this->readMatrix($matrix, function($v, $r, $c, $numRows, $numCols) use(&$antinodes, &$matrix)
        {
            if(array_key_exists($r.'_'.$c, $antinodes)){
                $matrix[$r][$c] = '#';
            }
        });

        $this->renderMatrix($matrix);*/

        return count($antinodes);
    }

    protected function runPart2()
    {
        $matrix = $this->fileMatrix(false, '');
        $numRows = $this->matrixNumRows($matrix);
        $numCols = $this->matrixNumCols($matrix);

        $antennas = [];
        $this->readMatrix($matrix, function($v, $r, $c, $numRows, $numCols) use(&$antennas)
        {
            if($v !== '.')
            {
                if(!array_key_exists($v, $antennas)){
                    $antennas[$v] = [];
                }
                $antennas[$v][] = [$r, $c];
            }
        });

        $antinodes = [];
        foreach($antennas as $digit => $positions)
        {
            for($i=0; $i<count($positions); ++$i)
            {
                for($j=$i+1; $j<count($positions); ++$j)
                {
                    $r1 = $positions[$i][0];
                    $c1 = $positions[$i][1];
                    $r2 = $positions[$j][0];
                    $c2 = $positions[$j][1];
                    $dr = abs($r2 - $r1);
                    $dc = abs($c2 - $c1);

                    $antinodes[implode('_', $positions[$i])] = $positions[$i];
                    $antinodes[implode('_', $positions[$j])] = $positions[$j];

                    if($r1 < $r2)
                    {
                        if($c1 < $c2)
                        {
                            $res = 1;
                            while($r1 - $res * $dr >= 0 && $c1 - $res * $dc >= 0)
                            {
                                $a = [ $r1 - $res * $dr, $c1 - $res * $dc ]; 
                                $antinodes[implode('_', $a)] = $a;
                                $res++;
                            }

                            $res = 1;
                            while($r2 + $res * $dr < $numRows && $c2 + $res * $dc < $numCols)
                            {
                                $a = [ $r2 + $res * $dr, $c2 + $res * $dc ]; 
                                $antinodes[implode('_', $a)] = $a;
                                $res++;
                            }
                        }
                        else
                        { 
                            $res = 1;
                            while($r1 - $res * $dr >= 0 && $c1 + $res * $dc < $numCols)
                            {
                                $a = [ $r1 - $res * $dr, $c1 + $res * $dc ]; 
                                $antinodes[implode('_', $a)] = $a;
                                $res++;
                            }

                            $res = 1;
                            while($r2 + $res * $dr < $numRows && $c2 - $res * $dc >= 0)
                            {
                                $a  = [ $r2 + $res * $dr, $c2 - $res * $dc ]; 
                                $antinodes[implode('_', $a)] = $a;
                                $res++;
                            }
                        }
                    }else{
                        if($c1 < $c2)
                        {
                            $res = 1;
                            while($r2 - $res * $dr >= 0 && $c1 - $res * $dc >= 0)
                            {
                                $a = [ $r2 - $res * $dr, $c1 - $res * $dc ]; 
                                $antinodes[implode('_', $a)] = $a;
                                $res++;
                            }

                            $res = 1;
                            while($r1 + $res * $dr < $numRows && $c2 + $res * $dc < $numCols)
                            {
                                $a = [ $r1 + $res * $dr, $c2 + $res * $dc ]; 
                                $antinodes[implode('_', $a)] = $a;
                                $res++;
                            }
                        }
                        else
                        {
                            $res = 1;
                            while($r2 - $res * $dr >= 0 && $c1 + $res * $dc < $numCols)
                            {
                                $a = [ $r2 - $res * $dr, $c1 + $res * $dc ]; 
                                $antinodes[implode('_', $a)] = $a;
                                $res++;
                            }

                            $res = 1;
                            while($r1 + $res * $dr < $numRows && $c2 - $res * $dc >= 0)
                            {
                                $a = [ $r1 + $res * $dr, $c2 - $res * $dc ]; 
                                $antinodes[implode('_', $a)] = $a;
                                $res++;
                            }
                        }
                    }
                }
            }
        }

        /*foreach($antinodes as $key => $pos)
        {
            if(!($pos[0] >= 0 && $pos[0] < $numRows && $pos[1] >= 0 && $pos[1] < $numCols))
            {
                unset($antinodes[$key]);
            }
        }*/
        
        $this->readMatrix($matrix, function($v, $r, $c, $numRows, $numCols) use(&$antinodes, &$matrix)
        {
            if(array_key_exists($r.'_'.$c, $antinodes)){
                $matrix[$r][$c] = '#';
            }
        });

        $this->renderMatrix($matrix);

        return count($antinodes);
    }
}
