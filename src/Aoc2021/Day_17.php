<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_17 extends Aoc
{
    protected $targetMinX = null;
    protected $targetMaxX = null;
    protected $targetMinY = null;
    protected $targetMaxY = null;

    protected function init()
    {
        $str = trim($this->lines[0]);

        $str = Str::after($str, 'area: x=');
        $this->targetMinX = Str::before($str, '..');
        $this->targetMaxX = Str::before(Str::after($str, '..'), ',');

        $str = Str::after($str, 'y=');
        $this->targetMinY = Str::before($str, '..');
        $this->targetMaxY = Str::before(Str::after($str, '..'), ',');
    }

    protected function runPart1()
    {
        $results = $this->results();
        return $this->arrayMax($results, 'maxY');
    }

    protected function runPart2()
    {
        $results = $this->results();
        return count($results);
    }

    protected function results()
    {
        $results = [];

        // On, teste la profondeur maximale d'abord :
        $tryY = $this->targetMinY;
        $hasReachResults = false;

        while(!$hasReachResults || $tryY <= -$this->targetMinY)
        {
            $resultsY = $this->resultsForY($tryY);

            foreach($resultsY as $resultY){
                $results[] = $resultY;    
            }

            if(count($resultsY) > 0){
                $hasReachResults = true;
            }

            //echo "=> Y = $tryY | results count : ".count($resultsY)."\n";

            /*if($tryY === 12)
            {
                echo "----------------------------------\n";
                echo "Y = $tryY : Num shoots : ".count($resultsY)."\n";
                echo "----------------------------------\n";
                $this->displayPoints($resultsY);
                exit;
            }*/

            ++$tryY;
        }
        
        return $results;
    }

    protected function resultsForY($y)
    {
        $tryMinX = 0;
        $tryMaxX = $this->targetMaxX;

        $results = [];

        for($x=$tryMinX; $x<=$tryMaxX; ++$x)
        {
            $result = $this->tryLaunch($x, $y);
            if($result['success'])
            {
                $results[] = $result;
            }
        }

        return $results;
    }

    protected function tryLaunch($initialForward, $initialUpward)
    {
        $forward = $initialForward;
        $upward = $initialUpward;

        $x = 0;
        $y = 0;

        $result = [
            'success' => false,
            'maxY' => 0,
            'forward' => $forward,
            'upward' => $upward,
            'points' => [[0, 0]],
        ];

        while($x <= $this->targetMaxX && $y >= $this->targetMinY)
        {
            $x += $forward;
            if($forward > 0){
                $forward -= 1;    
            }else if($forward < 0){
                $forward += 1;    
            }

            $y += $upward;
            $upward = $upward - 1;

            $result['points'][] = [$x, $y];
            $result['maxY'] = max($result['maxY'], $y);

            if($x >= $this->targetMinX && $x <= $this->targetMaxX && $y <= $this->targetMaxY && $y >= $this->targetMinY)
            {
                //echo "=> Works : $initialForward, $initialUpward\n";
                $result['success'] = true;
                break;
            }
        }

        return $result;
    }

    protected function displayPoints($results)
    {
        $maxY = $this->arrayMax($results, 'maxY');

        for($r=($maxY ? $maxY : 0); $r>=$this->targetMinY; $r--)
        {
            for($c=0; $c<$this->targetMaxX; $c++)
            { 
                $display = '.';
                foreach($results as $result)
                {
                    foreach($result['points'] as $point)
                    {
                        if($c === $point[0] && $r === $point[1])
                        {
                            $display = '#';
                        }
                    }
                }
                if($display === '.')
                {
                    if($c >= $this->targetMinX && $c <= $this->targetMaxX && $r >= $this->targetMinY && $r <= $this->targetMaxY)
                    {
                        $display = 'T';
                    }
                }
                echo $display;
            }
            echo "\n";
        }
    }
}
