<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_01 extends Aoc
{
    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $values = [];
        $sum = 0;
        foreach($this->lines as $line)
        {
            $digits = str_split($line, 1);

            $first = null;
            for($i=0; $i<count($digits); ++$i){
                if(is_numeric($digits[$i])){
                    $first = $digits[$i];
                    break;
                }
            }

            $last = null;
            for($i=count($digits) - 1; $i>=0; --$i){
                if(is_numeric($digits[$i])){
                    $last = $digits[$i];
                    break;
                }
            }
            $value = intVal($first.$last);
            $values[] = $value;
            $sum += $value;
        }
        return $sum;
    }

    protected function runPart2()
    {
        $strings = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

        $values = [];
        $sum = 0;
        foreach($this->lines as $lineIdx => $line)
        {
            $digits = str_split($line, 1);

            $first = null;
            for($i=0; $i<count($digits); ++$i)
            {
                if(is_numeric($digits[$i]))
                {
                    $first = $digits[$i];
                    break;
                }
                else
                {
                    foreach($strings as $strIdx => $string)
                    {
                        if(substr($line, $i, strlen($string)) === $string)
                        {
                            $first = $strIdx + 1;
                            break;
                        }
                    }
                    if($first){
                        break;
                    }
                }
            }

            $last = null;
            for($i=count($digits) - 1; $i>=0; --$i)
            {
                if(is_numeric($digits[$i]))
                {
                    $last = $digits[$i];
                    break;
                }
                else
                {
                    foreach($strings as $strIdx => $string)
                    {
                        $sub = substr($line, $i, strlen($string));
                        //if($lineIdx === 2){dump($sub);}
                        if($sub === $string)
                        {
                            $last = $strIdx + 1;
                            break;
                        }
                    }
                    if($last){
                        break;
                    }
                }
            }

            $value = intVal($first.$last);
            $values[] = $value;
            $sum += $value;

            if($lineIdx === 2){
                //dd($line, $first, $last, $value);
            }
        }

        return $sum;
    }
}
