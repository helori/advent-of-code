<?php

namespace Aoc\Aoc2022;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_02 extends Aoc
{
    protected $games = [];

    protected function init()
    {
        $this->lines = array_filter($this->lines);
        $this->games = array_map(function($game){
            return [$game[0], $game[2]];
        }, $this->lines);
    }

    protected function runPart1()
    {
        $result = 0;
        foreach($this->games as $game)
        {
            $result += $this->gameResult($game);
        }
        return $result;
    }

    protected function gameResult($game)
    {
        $alphabet = range('A', 'Z');

        $in1 = array_search($game[0], $alphabet);
        $in2 = array_search($game[1], $alphabet) - 23;

        return $this->score($in1, $in2);
    }

    protected function runPart2()
    {
        $result = 0;
        foreach($this->games as $game)
        {
            $result += $this->gameResult2($game);
        }
        return $result;
    }
    
    protected function gameResult2($game)
    {
        $alphabet = range('A', 'Z');

        $in1 = array_search($game[0], $alphabet);
        $in2 = array_search($game[1], $alphabet) - 23;

        if($in2 === 0)
        {
            $in2 = $in1-1;
            $in2 = ($in2 === -1) ? 2 : $in2;
        }
        else if($in2 === 1)
        {
            $in2 = $in1;
        }
        else if($in2 === 2)
        {
            $in2 = $in1+1;
            $in2 = ($in2 === 3) ? 0 : $in2;
        }

        return $this->score($in1, $in2);
    }

    protected function score($in1, $in2)
    {
        if($in1 === $in2)
        {
            $result = 3 + ($in1 + 1);
        }
        else if(($in1 === $in2 - 1) || ($in1 === 2 && $in2 === 0))
        {
            $result = 6 + ($in2 + 1);
        }
        else
        {
            $result = 0 + ($in2 + 1);
        }
        //dump($game, $result);
        return $result;
    }
}
