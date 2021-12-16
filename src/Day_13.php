<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_13 extends Aoc
{
    protected $dots = [];
    protected $instructions = [];
    protected $points = [];

    protected function init()
    {
        $this->lines = array_filter($this->lines);

        $this->dots = [];
        $this->instructions = [];
        $this->points = [];

        foreach($this->lines as $i => $line)
        {
            if(Str::startsWith($line, 'fold')){
                $instruction = Str::after($line, 'fold along ');
                $parts = explode('=', $instruction);
                $this->instructions[] = [$parts[0], intVal($parts[1])];
            }else{
                $this->dots[] = $this->toInts(explode(',', $line));
            }
        }
        //$this->dump($this->dots);
        //$this->dump($this->instructions);
    }

    protected function runPart1()
    {
        //$this->displayDots();
        $firstFold = $this->instructions[0];
        $newDots = $this->fold($firstFold);
        $this->dots = $newDots;
        //$this->displayDots();
        return count($newDots);
    }

    protected function runPart2()
    {
        foreach($this->instructions as $fold)
        {
            $newDots = $this->fold($fold);
            $this->dots = $newDots;
        }
        $this->displayDots();

        return 0;
    }

    protected function fold($fold)
    {
        $points = [];
        $newDots = [];

        foreach($this->dots as $dot)
        {
            $x = $dot[0];
            $y = $dot[1];

            if($fold[0] === 'x'){
                $foldX = $fold[1];
                if($x > $foldX){
                    $x = $foldX - ($x - $foldX);
                }
            }else if($fold[0] === 'y'){
                $foldY = $fold[1];
                if($y > $foldY){
                    $y = $foldY - ($y - $foldY);
                }
            }

            $key = $x.'_'.$y;
            if(!isset($points[$key])){
                $points[$key] = true;
                $newDots[] = [$x, $y];
            }
        }
        return $newDots;
    }

    protected function displayDots()
    {
        $maxX = null;
        $maxY = null;
        $points = [];

        foreach($this->dots as $dot)
        {
            $maxX = $maxX ? max($maxX, $dot[0]) : $dot[0];
            $maxY = $maxY ? max($maxY, $dot[1]) : $dot[1];
            if(!isset($points[$dot[0]])){
                $points[$dot[0]] = [];
            }
            $points[$dot[0]][] = $dot[1];
        }

        echo "-----------------\n";
        for($y=0; $y<=$maxY; ++$y)
        {
            for($x=0; $x<=$maxX; ++$x)
            {
                echo (isset($points[$x]) && in_array($y, $points[$x])) ? '#' : '.';
            }
            echo "\n";
        }
        echo "-----------------\n";
    }
}
