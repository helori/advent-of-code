<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_08 extends Aoc
{
    protected function init()
    {
        $this->lines = array_filter($this->lines);
    }

    protected function runPart1()
    {
        $count = 0;
        foreach($this->lines as $line)
        {
            $parts = explode(' | ', $line);
            $observations = explode(' ', $parts[0]);
            $digits = explode(' ', $parts[1]);

            foreach($digits as $digit)
            {
                if(in_array(strlen($digit), [2, 3, 4, 7])){
                    ++$count;
                }
            }
        }
        return $count;
    }

    protected function runPart2()
    {
        $numSegments = [
            0 => 6,
            1 => 2,
            2 => 5,
            3 => 5,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 3,
            8 => 7,
            9 => 6,
        ];
        
        $codes = [
            0 => null,
            1 => null,
            2 => null,
            3 => null,
            4 => null,
            5 => null,
            6 => null,
            7 => null,
            8 => null,
            9 => null,
        ];

        $sum = 0;
        
        foreach($this->lines as $line)
        {
            $parts = explode(' | ', $line);
            $observations = explode(' ', $parts[0]);
            $digits = explode(' ', $parts[1]);
            $all = array_merge($observations, $digits);
            
            $tests = 10;

            for($t=0; $t<$tests; ++$t)
            {
                foreach($all as $digit)
                {
                    $length = strlen(trim($digit));

                    $sub4 = null;
                    if(!is_null($codes[4])){
                        if(!is_null($codes[1])){
                            $sub4 = $this->removeLetters($codes[4], $codes[1]);
                        }else if(!is_null($codes[7])){
                            $sub4 = $this->removeLetters($codes[4], $codes[7]);
                        }
                    }

                    $sub05 = null;
                    if(!is_null($codes[0]) && !is_null($codes[5])){
                        $sub05 = $this->removeLetters($codes[0], $codes[5]);
                    }

                    if($length === 2){
                        $codes[1] = $digit;
                    }else if($length === 3){
                        $codes[7] = $digit;
                    }else if($length === 4){
                        $codes[4] = $digit;
                    }else if($length === 7){
                        $codes[8] = $digit;
                    }
                    else if($length === 6)
                    {
                        if($this->notContains($digit, $codes, 1))
                        {
                            $codes[6] = $digit;
                        }
                        if($this->contains($digit, $codes, 3))
                        {
                            $codes[9] = $digit;
                        }
                        if($this->contains($digit, $codes, 4))
                        {
                            $codes[9] = $digit;
                        }
                        if($this->notContains($digit, $codes, 5))
                        {
                            $codes[0] = $digit;
                        }
                        if($this->notContains($digit, $codes, 7))
                        {
                            $codes[6] = $digit;
                        }
                        if(!is_null($sub4) && !$this->containsLetters($digit, $sub4))
                        {
                            $codes[0] = $digit;
                        }
                    }
                    else if($length === 5)
                    {
                        if($this->contains($digit, $codes, 1))
                        {
                            $codes[3] = $digit;
                        }
                        if($this->contains($digit, $codes, 7))
                        {
                            $codes[3] = $digit;
                        }
                        if(!is_null($sub4) && $this->containsLetters($digit, $sub4))
                        {
                            $codes[5] = $digit;
                        }
                        if(!is_null($sub05) && $this->containsLetters($digit, $sub05))
                        {
                            $codes[2] = $digit;
                        }
                    }
                }
            }

            
            foreach($digits as $i => $digit)
            {
                $parts = str_split($digit);
                asort($parts);
                $digits[$i] = implode('', $parts);
            }

            foreach($codes as $i => $val)
            {
                $parts = str_split($val);
                asort($parts);
                $codes[$i] = implode('', $parts);
            }
            
            $nums = [];
            $val = 0;

            foreach($digits as $i => $digit)
            {
                $num = array_search($digit, $codes);
                if($num === false){
                    dd("Missing number !");
                }
                $val += $num * pow(10, count($digits) - ($i + 1));

                $nums[] = $num;
            }

            $sum += $val;
        }

        return $sum;
    }

    protected function contains($digit, $codes, $number)
    {
        return !is_null($codes[$number]) && $this->containsLetters($digit, $codes[$number]);
    }

    protected function containsLetters($digit, $letters)
    {
        $result = true;
        $digitArray = str_split($digit);
        $letters = str_split($letters);
        foreach($letters as $letter){
            $result &= in_array($letter, $digitArray);
        }
        return $result;
    }

    protected function notContains($digit, $codes, $number)
    {
        return !is_null($codes[$number]) && !$this->containsLetters($digit, $codes[$number]);
    }

    protected function removeLetters($str, $lettersStr)
    {
        $letters = str_split($lettersStr);
        $chars = str_split($str);
        
        foreach($letters as $letter){
            $key = array_search($letter, $chars);
            if($key !== false){
                unset($chars[$key]);
            }
        }
        return implode('', $chars);
    }
}
