<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_02 extends Aoc
{
    protected $matrix;

    protected function init()
    {
        $this->matrix = $this->fileMatrix(true);
        //$this->renderMatrix($matrix);
    }

    protected function runPart1()
    {
        $valids = 0;
        foreach($this->matrix as $row)
        {
            $valid = $this->isValid($row);
            $valids += $valid ? 1 : 0;
        }
        return $valids;
    }

    protected function runPart2()
    {
        $valids = 0;
        foreach($this->matrix as $row)
        {
            $valid = $this->isValid($row);
            $removeIdx = 0;
            while(!$valid && $removeIdx < count($row))
            {
                $report = $row;
                unset($report[$removeIdx]);
                $report = array_values($report);
                $valid = $this->isValid($report);
                ++$removeIdx;
            }
            $valids += $valid ? 1 : 0;
        }
        return $valids;
    }

    protected function isValid($report)
    {
        $isAsc = $report[1] > $report[0];
        $valid = true;
        for($i=1; $i<count($report); ++$i)
        {
            $diff = $report[$i] - $report[$i - 1];
            if(($isAsc && $diff < 0) || (!$isAsc && $diff > 0) || abs($diff) < 1 || abs($diff) > 3)
            {
                $valid = false;
                break;
            }
        }
        return $valid;
    }
}
