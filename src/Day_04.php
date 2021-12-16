<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_04 extends Aoc
{
    protected $numbers = [];
    protected $grids = [];

    protected function init()
    {
        $this->numbers = array_map(function($item){
            return intVal($item);
        }, explode(',', $this->lines[0]));
        
        $gridLines = $this->lines;
        unset($gridLines[0]);
        unset($gridLines[1]);
        $gridLines = array_values($gridLines);

        $this->grids = [];
        $grid = [];
        foreach($gridLines as $i => $gridLine){
            if(!$gridLine){
                $this->grids[] = $grid;
                $grid = [];
            }else{
                $gridLineSingleSpace = preg_replace('!\s+!', ' ', $gridLine);
                $nums = array_map(function($item){
                    return intVal($item);
                }, explode(' ', $gridLineSingleSpace));
                $grid[] = $nums;
            }
        }
        //$this->dd($this->numbers);
        //$this->dd($this->grids[count($this->grids) - 1]);
    }

    protected function runPart1()
    {
        $winningNumberIdx = count($this->numbers);
        $winningGridIdx = null;

        foreach($this->grids as $gridIdx => $rows)
        {
            $gridWinningNumberIdx = $this->getGridWinningNumberIdx($rows);

            if($gridWinningNumberIdx < $winningNumberIdx)
            {
                $winningNumberIdx = $gridWinningNumberIdx;
                $winningGridIdx = $gridIdx;
            }
        }

        return $this->score($winningGridIdx, $winningNumberIdx);
    }

    protected function runPart2()
    {
        $winningNumberIdx = 0;
        $winningGridIdx = null;

        foreach($this->grids as $gridIdx => $rows)
        {
            $gridWinningNumberIdx = $this->getGridWinningNumberIdx($rows);

            if($gridWinningNumberIdx > $winningNumberIdx)
            {
                $winningNumberIdx = $gridWinningNumberIdx;
                $winningGridIdx = $gridIdx;
            }
        }

        return $this->score($winningGridIdx, $winningNumberIdx);
    }

    protected function getGridWinningNumberIdx($grid)
    {
        $gridWinningNumberIdx = count($this->numbers);

        // On parcourt les lignes de la grille :
        foreach($grid as $row)
        {
            // Index du nombre pour lequel la ligne est gagnante :
            $idx = $this->check($row);
            $gridWinningNumberIdx = min($gridWinningNumberIdx, $idx);
        }

        // On parcourt les colonnes de la grille :
        $cols = $this->matrixCols($grid);
        foreach($cols as $col)
        {
            // Index du nombre pour lequel la colonne est gagnante :
            $idx = $this->check($col);
            $gridWinningNumberIdx = min($gridWinningNumberIdx, $idx);
        }

        return $gridWinningNumberIdx;
    }

    protected function check($line)
    {
        for($i=count($line); $i<=count($this->numbers); ++$i)
        {
            $subNumbers = array_slice($this->numbers, 0, $i);
            $diff = array_diff($line, $subNumbers);
            if(empty($diff)){
                return count($subNumbers) - 1;
            }
        }
        return null;
    }

    protected function score($winningGridIdx, $winningNumberIdx)
    {
        $grid = $this->grids[$winningGridIdx];
        $subNumbers = array_slice($this->numbers, 0, $winningNumberIdx + 1);
        
        $sum = 0;
        foreach($grid as $row)
        {
            foreach($row as $number){
                if(!in_array($number, $subNumbers)){
                    $sum += $number;
                }
            }
        }
        
        return $sum * $this->numbers[$winningNumberIdx];
    }
}
