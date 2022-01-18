<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_05 extends Aoc
{
    protected $maxX = null;
    protected $maxY = null;
    protected $linesData = null;

    protected function init()
    {
        $this->lines = array_filter($this->lines);

        $this->linesData = [];
        $this->maxX = 0;
        $this->maxY = 0;

        foreach($this->lines as $i => $value)
        {
            $points = explode(' -> ', $value);
            $point1 = explode(',', $points[0]);
            $point2 = explode(',', $points[1]);
            $points = [
                'x1' => intVal($point1[0]),
                'y1' => intVal($point1[1]),
                'x2' => intVal($point2[0]),
                'y2' => intVal($point2[1]),
            ];
            
            $this->linesData[] = $points;
            $this->maxX = max($this->maxX, $points['x1'], $points['x2']);
            $this->maxY = max($this->maxY, $points['y1'], $points['y2']);
        }
    }

    protected function runPart1()
    {
        $points = [];
        foreach($this->linesData as $line)
        {
            // horizontal and vertical
            if(($line['x1'] === $line['x2']) || ($line['y1'] === $line['y2']))
            {
                $lineMinX = min($line['x1'], $line['x2']);
                $lineMaxX = max($line['x1'], $line['x2']);
                $lineMinY = min($line['y1'], $line['y2']);
                $lineMaxY = max($line['y1'], $line['y2']);

                for($x=$lineMinX; $x<=$lineMaxX; ++$x)
                {
                    for($y=$lineMinY; $y<=$lineMaxY; ++$y)
                    {
                        $key = implode(',', [$x, $y]);
                        if(!isset($points[$key])){
                            $points[$key] = 0;
                        }
                        ++$points[$key];
                    }
                }
            }
        }

        $points = array_filter($points, function($item){
            return ($item >= 2);
        });

        return count($points);
    }

    protected function runPart2()
    {
        $points = [];
        foreach($this->linesData as $line)
        {
            // horizontal and vertical
            if(($line['x1'] === $line['x2']) || ($line['y1'] === $line['y2']))
            {
                $lineMinX = min($line['x1'], $line['x2']);
                $lineMaxX = max($line['x1'], $line['x2']);
                $lineMinY = min($line['y1'], $line['y2']);
                $lineMaxY = max($line['y1'], $line['y2']);

                for($x=$lineMinX; $x<=$lineMaxX; ++$x)
                {
                    for($y=$lineMinY; $y<=$lineMaxY; ++$y)
                    {
                        $key = implode(',', [$x, $y]);
                        if(!isset($points[$key])){
                            $points[$key] = 0;
                        }
                        ++$points[$key];
                    }
                }
            }
            else
            {
                $diag1 = false;
                if(($line['x1'] < $line['x2']) && ($line['y1'] < $line['y2'])){
                    $diag1 = true;
                }else if(($line['x1'] > $line['x2']) && ($line['y1'] > $line['y2'])){
                    $diag1 = true;
                }

                if($diag1)
                {
                    $x1 = min($line['x1'], $line['x2']);
                    $y1 = min($line['y1'], $line['y2']);
                    $x2 = max($line['x1'], $line['x2']);
                    $y2 = max($line['y1'], $line['y2']);

                    if(($x2 - $x1) != ($y2 - $y1)){
                        dd($x1, $x2, $y1, $y2);
                    }
                    for($i=0; $i<=($x2 - $x1); ++$i)
                    {
                        $x = $x1 + $i;
                        $y = $y1 + $i;

                        $key = implode(',', [$x, $y]);
                        if(!isset($points[$key])){
                            $points[$key] = 0;
                        }
                        ++$points[$key];
                    }
                }
                else
                {
                    $x1 = min($line['x1'], $line['x2']);
                    $y1 = max($line['y1'], $line['y2']);
                    $x2 = max($line['x1'], $line['x2']);
                    $y2 = min($line['y1'], $line['y2']);

                    for($i=0; $i<=($x2 - $x1); ++$i)
                    {
                        $x = $x1 + $i;
                        $y = $y1 - $i;

                        $key = implode(',', [$x, $y]);
                        if(!isset($points[$key])){
                            $points[$key] = 0;
                        }
                        ++$points[$key];
                    }
                }
            }
        }

        $points = array_filter($points, function($item){
            return ($item >= 2);
        });

        return count($points);
    }
}
