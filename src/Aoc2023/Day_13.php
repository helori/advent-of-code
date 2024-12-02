<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_13 extends Aoc
{
    protected $patterns = [];

    protected function init()
    {
        $this->patterns = [];
        $pattern = [];

        foreach($this->lines as $i => $line)
        {
            if($line){
                $pattern[] = str_split($line);
            }else{
                $this->patterns[] = $pattern;
                $pattern = [];
            }
        }
        if(count($pattern) > 0)
        {
            $this->patterns[] = $pattern;
        }
    }

    protected function runPart1()
    {
        $cols = [];
        $rows = [];
        foreach($this->patterns as $i => $pattern)
        {
            $symRows = $this->reflectionAxe($pattern);
            if($symRows !== null){
                $rows[$i] = $symRows+1;
            }

            $pattern = $this->matrixCols($pattern);
            $symCols = $this->reflectionAxe($pattern);
            if($symCols !== null){
                $cols[$i] = $symCols+1;
            }
        }

        return array_sum($cols) + 100 * array_sum($rows);
    }

    protected function reflectionCheck($matrix, $axeIdx)
    {
        $count = min($axeIdx, count($matrix) - $axeIdx - 2);
        
        for($r=0; $r<=$count; ++$r)
        {
            if($matrix[$axeIdx - $r] !== $matrix[$axeIdx + 1 + $r]){
                return false;
            }
        }
        return true;
    }

    protected function reflectionAxe($matrix)
    {
        for($r=1; $r<count($matrix); ++$r)
        {
            if($matrix[$r-1] === $matrix[$r]){
                if($this->reflectionCheck($matrix, $r-1)){
                    return $r-1;
                }
            }
        }
        return null;
    }

    protected function runPart2()
    {
        $rows = [];
        foreach($this->patterns as $i => $pattern)
        {
            $symRowsOld = $this->reflectionAxe($pattern);
            $symRow = null;

            $this->readMatrix($pattern, function($v, $r, $c) use($pattern, &$rows, $symRowsOld, &$symRow)
            {
                $pattern2 = $pattern;
                $pattern2[$r][$c] = ($v === '.') ? '#' : '.';

                $symRowsNew = $this->reflectionAxe($pattern2);

                if($symRowsNew !== null && $symRowsNew !== $symRowsOld){
                    $symRow = $symRowsNew;
                    return true;
                }

                return false;
            });

            if($symRow === null){
                $symRow = $symRowsOld;
            }
            if($symRow === null){
                $rows[] = $symRow+1;
            }
        }

        return 100 * array_sum($rows);
    }

    protected function reflectionLine($matrix)
    {
        for($r=1; $r<count($matrix); ++$r)
        {
            $axeIdx = $r-1;
            $count = min($axeIdx, count($matrix) - $axeIdx - 2);
            $isOk = $this->reflectionCheck2($matrix, $axeIdx, 0, $count, true);

            if($isOk){
                return $axeIdx;
            }
        }
        return null;
    }

    protected function diff($r1, $r2)
    {
        $diff = [];
        foreach($r1 as $i => $v){
            if($r2[$i] !== $v){
                $diff[] = [
                    'idx' => $i,
                    'v1' => $r1[$i],
                    'v2' => $r2[$i],
                ];
            }
        }
        return $diff;
    }

    protected function reflectionCheck2($buffer, $axeIdx, $offset, $count, $diffAllowed)
    {
        $isOk = true;
        $hasDiff = false;

        for($i=$offset; $i<=$count; ++$i)
        {
            $row1Idx = $axeIdx - $i;
            $row2Idx = $axeIdx + 1 + $i;

            $row1 = $buffer[$row1Idx];
            $row2 = $buffer[$row2Idx];
            
            $diff = $this->diff($row1, $row2);

            // Identiques
            if(count($diff) === 0){
                continue;
            }

            // Plus que 1 différence
            if(count($diff) > 1){
                $isOk = false;
                break;
            }

            // Une seule différence
            if($diffAllowed && (count($diff) === 1))
            {
                $hasDiff = true;
                //$this->renderMatrix($buffer);
                //echo "\n";

                // Option 1
                $row1[$diff[0]['idx']] = $diff[0]['v2'];
                $buffer[$row1Idx] = $row1;
                $isOk = $this->reflectionCheck2($buffer, $axeIdx, $i, $count, false);

                //$this->renderMatrix($buffer);
                //exit;

                if(!$isOk)
                {
                    // Option 2
                    $row1[$diff[0]['idx']] = $diff[0]['v1'];
                    $buffer[$row1Idx] = $row1;

                    $row2[$diff[0]['idx']] = $diff[0]['v1'];
                    $buffer[$row2Idx] = $row2;
                    
                    $isOk = $this->reflectionCheck2($buffer, $axeIdx, $i, $count, false);
                }
                break;
            }
        }
        return $isOk && ($diffAllowed ? $hasDiff : true);
    }
}
