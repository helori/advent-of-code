<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_14 extends Aoc
{
    protected $robots;
    protected $matrix;
    protected $map;

    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $this->robots = [];
        foreach($this->lines as $line)
        {
            $p = explode(',', Str::before(Str::after($line, 'p='), ' '));
            $v = explode(',', Str::after($line, 'v='));
            $this->robots[] = [
                intVal($p[0]), // x
                intVal($p[1]), // y
                intVal($v[0]), // vx
                intVal($v[1]), // vy
            ];
        }
        
        $maxX = 101;
        $maxY = 103;
        
        // Part 1
        /*$seconds = 100;
        for($s=0; $s<$seconds; $s++)
        {
            $this->move($maxX, $maxY);
        }*/

        $symetry = false;
        $s = 0;
        while(!$symetry)
        {
            $s++;
            $this->move($maxX, $maxY);
            //if($s > 1000000)
            {
                //$this->buildMatrix($maxX, $maxY);
                //$symetry = $this->checkSymetry();

                $symetry = $this->checkSymetry2($maxX, $maxY);
                if($s % 10000 === 0){
                    dump($s);
                }
            }
        }
        dd("FOUND seconds ".$s);

        $sideX = ($maxX - 1) / 2;
        $sideY = ($maxY - 1) / 2;

        $q1 = 0;
        $q2 = 0;
        $q3 = 0;
        $q4 = 0;

        foreach($this->robots as $robot)
        {
            $x = $robot[0];
            $y = $robot[1];

            if($x < $sideX && $y < $sideY) { $q1++; }
            else if($x < $sideX && $y > $sideY) { $q2++; }
            else if($x > $sideX && $y > $sideY) { $q3++; }
            else if($x > $sideX && $y < $sideY) { $q4++; }
        }

        return $q1 * $q2 * $q3 * $q4;
    }

    protected function checkSymetry()
    {
        $symetry = true;
        $r = 0;
        while($symetry)
        {
            $row = $this->matrix[$r];
            $i1 = array_search('X', $row);
            $row = array_reverse($row);
            $i2 = array_search('X', $row);
            $symetry &= ($i1 === $i2);
            $r++;
        }
        return $symetry;
    }

    protected function checkSymetry2($maxX, $maxY)
    {
        $this->map = [];
        foreach($this->robots as &$robot)
        {
            if(!array_key_exists($robot[1], $this->map)){
                $this->map[$robot[1]] = [];
            }
            $this->map[$robot[1]][] = $robot[0];
        }
        $symetry = true;
        foreach($this->map as $r => $cols)
        {
            $c1 = min($cols);
            $c2 = max($cols);
            $symetry &= ($c1 === ($maxX-1 - $c2));
            if(!$symetry){
                break;
            }
        }
        return $symetry;
    }
    
    protected function move($maxX, $maxY)
    {
        foreach($this->robots as &$robot)
        {
            $x = $robot[0];
            $y = $robot[1];
            $vx = $robot[2];
            $vy = $robot[3];

            $x += $vx;
            $y += $vy;

            if($x >= $maxX) { $x = $x - $maxX; }
            if($y >= $maxY) { $y = $y - $maxY; }

            if($x < 0) { $x = $x + $maxX; }
            if($y < 0) { $y = $y + $maxY; }

            $robot[0] = $x;
            $robot[1] = $y;
        }
        //dump($this->robots);
    }

    protected function buildMatrix($maxX, $maxY)
    {
        $this->matrix = [];
        for($r=0; $r<$maxY; $r++)
        {
            $row = [];
            for($c=0; $c<$maxX; $c++)
            {
                $row[$c] = '.';
            }
            $this->matrix[$r] = $row;
        }
        foreach($this->robots as &$robot)
        {
            $c = $robot[0];
            $r = $robot[1];
            $this->matrix[$r][$c] = 'X';
            //$this->matrix[$r][$c] = $this->matrix[$r][$c] === '.' ? 1 : $this->matrix[$r][$c]+1;
        }
    }

    protected function runPart2()
    {
        return 0;
    }
}
