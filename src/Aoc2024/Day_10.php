<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_10 extends Aoc
{
    protected $map;
    protected $numRows;
    protected $numCols;
    protected $trailheads;
    
    protected function init()
    {
        $this->map = $this->fileMatrix(true, '');
        $this->numRows = $this->matrixNumRows($this->map);
        $this->numCols = $this->matrixNumCols($this->map);
        $this->trailheads = [];
        $this->readMatrix($this->map, function($v, $r, $c) {
            if($v === 0){
                $this->trailheads[] = [
                    [ 'r' => $r, 'c' => $c ]
                ];
            }
        });

        foreach($this->trailheads as $i => &$positions)
        {
            $positions = $this->nextPositions($positions, 1);
        }
    }

    protected function runPart1()
    {
        foreach($this->trailheads as $i => $positions)
        {
            $this->trailheads[$i] = array_values(array_unique(array_map(function($pos){
                return $pos['r'].'_'.$pos['c'];
            }, $positions)));
        }
        return $this->score();
    }

    protected function runPart2()
    {
        return $this->score();
    }

    protected function score()
    {
        $score = 0;
        foreach($this->trailheads as $positions)
        {
            $score += count($positions);
        }
        return $score;
    }

    protected function nextPositions($positions, $height)
    {
        $newPositions = [];
        for($i=0; $i<count($positions); ++$i)
        {
            $nexts = $this->findNeighboors($positions[$i], $height);
            $newPositions = array_merge($newPositions, $nexts);
        }
        $positions = $newPositions;

        if($height < 9){
            $positions = $this->nextPositions($positions, $height+1);
        }

        return $positions;
    }

    protected function findNeighboors($pos, $height)
    {
        $nexts = [];

        if($pos['r'] > 0 && $this->map[$pos['r']-1][$pos['c']] === $height) {
            $nexts[] = [ 'r' => $pos['r'] - 1, 'c' => $pos['c'] ];
        }
        if($pos['r'] < $this->numRows-1 && $this->map[$pos['r']+1][$pos['c']] === $height) {
            $nexts[] = [ 'r' => $pos['r'] + 1, 'c' => $pos['c'] ];
        }
        if($pos['c'] > 0 && $this->map[$pos['r']][$pos['c']-1] === $height) {
            $nexts[] = [ 'r' => $pos['r'], 'c' => $pos['c']-1 ];
        }
        if($pos['c'] < $this->numCols-1 && $this->map[$pos['r']][$pos['c']+1] === $height) {
            $nexts[] = [ 'r' => $pos['r'], 'c' => $pos['c']+1 ];
        }

        return $nexts;
    }
}
