<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_02 extends Aoc
{
    protected $games = [];

    protected function init()
    {
        $this->games = [];
        foreach($this->lines as $line)
        {
            $idx = intVal(Str::before(Str::after($line, 'Game '), ':'));

            $game = [
                'index' => $idx,
                'sets' => [],
            ];

            $sets = explode('; ', Str::after($line, ': '));
            
            foreach($sets as $set)
            {
                $cubes = explode(', ', $set);
                $setData = [];
                foreach($cubes as $cube)
                {
                    $parts = explode(' ', $cube);
                    $setData[$parts[1]] = intVal($parts[0]);
                }
                $game['sets'][] = $setData;
            }
            $this->games[] = $game;
        }
    }

    protected function runPart1()
    {
        $red = 12;
        $green = 13;
        $blue = 14;

        $sum = 0;

        foreach($this->games as $game)
        {
            $valid = true;
            foreach($game['sets'] as $set)
            {
                if((isset($set['green']) && $set['green'] > $green) ||
                    (isset($set['red']) && $set['red'] > $red) ||
                    (isset($set['blue']) && $set['blue'] > $blue))
                {
                    $valid = false;
                }
            }
            if($valid){
                $sum += $game['index'];
            }
        }

        return $sum;
    }

    protected function runPart2()
    {
        $sum = 0;

        foreach($this->games as $game)
        {
            $red = 0;
            $green = 0;
            $blue = 0;

            foreach($game['sets'] as $set)
            {
                if(isset($set['red'])){
                    $red = max($red, $set['red']);
                }
                if(isset($set['green'])){
                    $green = max($green, $set['green']);
                }
                if(isset($set['blue'])){
                    $blue = max($blue, $set['blue']);
                }
            }

            $power = $red * $green * $blue;
            $sum += $power;
        }
        return $sum;
    }
}
