<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_11 extends Aoc
{
    protected $matrix = [];
    protected $rows = 0;
    protected $cols = 0;
    protected $emptyRowsIdx = [];
    protected $emptyColsIdx = [];
    protected $galaxies = [];

    protected function init()
    {
        $this->matrix = [];
        $this->emptyRowsIdx = [];
        $this->emptyRowsIdx = [];
        $this->galaxies = [];

        foreach($this->lines as $line)
        {
            $this->matrix[] = str_split($line);
        }
        $this->rows = $this->matrixNumRows($this->matrix);
        $this->cols = $this->matrixNumCols($this->matrix);

        for($i = $this->rows - 1; $i >= 0 ; --$i)
        {
            $row = $this->matrix[$i];
            if($this->onlyDots($row)){
                $this->emptyRowsIdx[] = $i;
            }
        }

        $cols = $this->matrixCols($this->matrix);
        for($i = $this->cols - 1; $i >= 0; --$i)
        {
            $col = $cols[$i];
            if($this->onlyDots($col)){
                $this->emptyColsIdx[] = $i;
            }
        }

        $this->readMatrix($this->matrix, function($v, $r, $c)
        {
            if($v === '#'){
                $this->galaxies[] = [$r, $c];
            }
        });
    }

    protected function runPart1()
    {
        return $this->distancesForDuplicates(2);
    }

    protected function runPart2()
    {
        return $this->distancesForDuplicates(1000000);
    }

    protected function distancesForDuplicates($duplicates)
    {
        $distances = [];
        foreach($this->galaxies as $i => $g1)
        {
            for($j = $i+1; $j < count($this->galaxies); $j++)
            {
                $g2 = $this->galaxies[$j];

                $r1 = min($g1[0], $g2[0]);
                $r2 = max($g1[0], $g2[0]);
                $c1 = min($g1[1], $g2[1]);
                $c2 = max($g1[1], $g2[1]);

                $distance = 0;
                for($r = $r1+1; $r <= $r2; $r++)
                {
                    $distance += in_array($r, $this->emptyRowsIdx) ? $duplicates : 1;
                }
                for($c = $c1+1; $c <= $c2; $c++)
                {
                    $distance += in_array($c, $this->emptyColsIdx) ? $duplicates : 1;
                }

                $distances[] = $distance;
            }
        }

        return array_sum($distances);
    }

    protected function onlyDots($values)
    {
        $counts = array_count_values($values);
        return isset($counts['.']) && (count($values) === $counts['.']);
    }
}
