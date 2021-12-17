<?php

namespace Aoc;

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
        $bestY = 0;

        foreach($results as $result){
            $bestY = max($bestY, $result['maxY']);
        }
        
        return $bestY;
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

        while(!$hasReachResults || $tryY < -$this->targetMinY)
        {
            $resultsY = $this->resultsForY($tryY);

            foreach($resultsY as $resultY){
                $results[] = $resultY;    
            }

            if(count($resultsY) > 0){
                $hasReachResults = true;
            }

            //echo "=> Y = $tryY | results count : ".count($resultsY)."\n";

            ++$tryY;
        }
        
        return $results;
    }

    protected function resultsForY($y)
    {
        $tryMinX = 0;
        $tryMaxX = $this->targetMaxX;

        $results = [];
        $maxY = null;

        for($x=$tryMinX; $x<=$tryMaxX; ++$x)
        {
            $result = $this->tryLaunch($x, $y);
            if($result['success'])
            {
                $results[] = $result;
                $maxY = is_null($maxY) ? $result['maxY'] : max($maxY, $result['maxY']);
            }
        }

        return $results;
    }

    protected function tryLaunch($forward, $upward)
    {
        $initialForward = $forward;
        $initialUpward = $upward;

        $x = 0;
        $y = 0;

        $result = [
            'success' => false,
            'maxY' => 0,
            'forward' => $forward,
            'upward' => $upward,
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

            $result['maxY'] = max($result['maxY'], $y);

            //echo "Position : $x, $y\n";

            if($x >= $this->targetMinX && $x <= $this->targetMaxX && $y <= $this->targetMaxY && $y >= $this->targetMinY)
            {
                //echo "=> Works : $initialForward, $initialUpward\n";
                $result['success'] = true;
                break;
            }
        }

        return $result;
    }
}
