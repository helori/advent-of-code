<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_07 extends Aoc
{
    protected $data = [];

    protected function init()
    {
        $this->data = [];
        foreach($this->lines as $line)
        {
            $this->data[] = [
                'value' => intVal(Str::before($line, ':')),
                'nums' => array_map(function($num){
                    return intVal($num);
                }, explode(' ', trim(Str::after($line, ':')))),
            ];
        }
    }

    protected function runPart1()
    {
        return $this->sumValids(false);
    }

    protected function runPart2()
    {
        return $this->sumValids(true);
    }

    protected function sumValids($alsoCombine)
    {
        $sum = 0;
        foreach($this->data as $d)
        {
            if($this->isValid($d['value'], $d['nums'], $alsoCombine)){
                $sum += $d['value'];
            }
        }
        return $sum;
    }

    protected function isValid($value, $nums, $alsoCombine)
    {
        $results = [ $nums[0] ];

        for($i=1; $i<count($nums); ++$i)
        {
            $newResults = [];
            foreach($results as $result)
            {
                $newResults[] = $result + $nums[$i];
                $newResults[] = $result * $nums[$i];

                if($alsoCombine)
                {
                    $newResults[] = $result.''.$nums[$i];
                }
            }
            $results = $newResults;
        }

        return in_array($value, $results);
    }
}
