<?php

namespace Aoc\Aoc2019;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_03 extends Aoc
{
    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $w1 = explode(',', $this->lines[0]);
        $w2 = explode(',', $this->lines[1]);

        $points1 = $this->pointsForWire($w1)['points'];
        $points2 = $this->pointsForWire($w2)['points'];

        $points = $this->intersections($points1, $points2);
        
        $distances = array_map(function($point){
            return abs($point[0]) + abs($point[1]);
        }, $points);

        return min($distances);
    }

    protected function intersections($points1, $points2)
    {
        $points = [];
        
        foreach($points1 as $x => $y1s)
        {
            if(isset($points2[$x]))
            {
                $y2s = $points2[$x];
                $ys = array_intersect($y1s, $y2s);

                foreach($ys as $y)
                {
                    $points[] = [$x, $y];
                }
            }
        }
        return $points;
    }

    protected function pointsForWire($w)
    {
        $points = [];
        $steps = [];

        $x = 0;
        $y = 0;

        $step = 0;

        foreach($w as $path)
        {
            $dir = substr($path, 0, 1);
            $num = intVal(substr($path, 1));
            
            for($i=1; $i<=$num; ++$i)
            {
                $step++;

                $x = ($dir === 'R') ? $x+1 : (($dir === 'L') ? $x-1 : $x);
                $y = ($dir === 'U') ? $y+1 : (($dir === 'D') ? $y-1 : $y);

                if(!isset($points[$x])){
                    $points[$x] = [];
                }
                if(!isset($steps[$x])){
                    $steps[$x] = [];
                }
                
                if(!in_array($y, $points[$x])){
                    $points[$x][] = $y;
                    $steps[$x][] = $step;
                }
            }
        }
        return [
            'points' => $points,
            'steps' => $steps,
        ];
    }

    protected function runPart2()
    {
        $w1 = explode(',', $this->lines[0]);
        $w2 = explode(',', $this->lines[1]);

        $d1 = $this->pointsForWire($w1);
        $d2 = $this->pointsForWire($w2);

        $points1 = $d1['points'];
        $points2 = $d2['points'];

        $steps1 = $d1['steps'];
        $steps2 = $d2['steps'];

        $points = $this->intersections($points1, $points2);
        
        $steps = array_map(function($point) use($points1, $steps1, $points2, $steps2)
        {
            $step1 = $this->stepsFor($point, $points1, $steps1);
            $step2 = $this->stepsFor($point, $points2, $steps2);
            return $step1 + $step2;
            
        }, $points);

        return min($steps);
    }

    public function stepsFor($point, $points, $steps)
    {
        $x = $point[0];
        $y = $point[1];

        $idx = array_search($y, $points[$x]);
        $step = $steps[$x][$idx];
            
        return $step;
    }
}
