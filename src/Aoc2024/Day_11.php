<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_11 extends Aoc
{
    protected $stones;
    protected $types;

    protected function init()
    {
        $this->stones = explode(' ', $this->lines[0]);
        $this->types = [];

        foreach($this->stones as $stone)
        {
            if(!isset($this->types[$stone])){
                $this->types[$stone] = 0;
            }
            $this->types[$stone] += 1;
        }
    }

    protected function runPart1()
    {
        return $this->stonesCountAfterBlinks($this->types, 25);
    }

    protected function runPart2()
    {
        return $this->stonesCountAfterBlinks($this->types, 75);
    }

    protected function stonesCountAfterBlinks($types, $times)
    {
        for($b=0; $b<$times; $b++)
        {
            $types = $this->blink($types);
        }
        return array_sum($types);
    }

    protected function blink($types)
    {
        $newTypes = [];
        foreach($types as $stone => $count)
        {
            $newStones = [];
            $this->blinkStone($stone, $newStones);
            
            foreach($newStones as $type)
            {
                if(!isset($newTypes[$type])){
                    $newTypes[$type] = 0;
                }
                $newTypes[$type] += $count;
            }
        }
        return $newTypes;
    }

    protected function blinkStone($stone, &$newStones)
    {
        $length = strlen($stone);
        $evenDigits = ($length % 2 === 0);

        if($stone == '0')
        {
            $newStones[] = '1';
        }
        else if($evenDigits)
        {
            $left = ltrim(Str::substr($stone, 0, $length/2), '0');
            $right = ltrim(Str::substr($stone, $length/2, $length/2), '0');

            $newStones[] = ($left === '') ? '0' : $left;
            $newStones[] = ($right === '') ? '0' : $right;
        }
        else
        {
            $newStones[] = (string)(intVal($stone) * 2024);
        }
    }
}
