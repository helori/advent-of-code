<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_18 extends Aoc
{
    protected function init()
    {

    }

    protected function runPart1()
    {
        $nums = array_filter($this->lines);
        $sum = $this->sumAll($nums);
        $pair = json_decode($sum, true);
        return $this->magnitude($pair);
    }

    protected function runPart2()
    {
        $nums = array_filter($this->lines);
        $max = 0;

        foreach($nums as $i => $num1)
        {
            foreach($nums as $j => $num2)
            {
                if($i !== $j)
                {
                    $sum = $this->sum($num1, $num2);
                    $pair = json_decode($sum, true);
                    $mag = $this->magnitude($pair);
                    $max = max($max, $mag);
                }
            }
        }
        return $max;
    }

    protected function reduce($pair)
    {
        $done = true;
        $steps = 0;
        //dump("-----------------");
        //dump("=> Reduce : ".$pair);

        while($done)
        {
            
            $r = $this->explode($pair);
            $done = $r['done'];
            $pair = $r['pair'];
            if(!$done)
            {
                $r = $this->split($pair);
                $done = $r['done'];
                $pair = $r['pair'];
            }
            if($done){
                ++$steps;
            }
        }
        //dump("=> Steps : ".$steps);
            
        return $pair;
    }

    protected function explode($str)
    {
        $chars = str_split($str);
        $opens = 0;
        $found = false;

        foreach($chars as $i => $char)
        {
            if($char === '['){
                $opens++;
            }else if($char === ']'){
                $opens--;
            }
            if($opens === 5)
            {
                $pairToExplodeStr = Str::before(substr($str, $i), ']').']';
                $pairToExplode = json_decode($pairToExplodeStr, true);
                $str = substr_replace($str, '0', $i, strlen($pairToExplodeStr));
                
                $message = "";

                $rightStr = substr($str, $i + 1);
                preg_match('/\d+/', $rightStr, $m, PREG_OFFSET_CAPTURE);
                if(sizeof($m))
                {
                    $rightNum = intVal($m[0][0]);
                    $rightPos = $m[0][1];
                    $sum = $pairToExplode[1] + $rightNum;
                    //$message .= " | Right : ".$pairToExplode[1]." + ".$rightNum." = ".$sum;
                    $str = substr_replace($str, $sum, $i + 1 + $rightPos, strlen($rightNum));
                }

                $leftStr = strrev(substr($str, 0, $i));
                preg_match('/\d+/', $leftStr, $m, PREG_OFFSET_CAPTURE);
                if(sizeof($m))
                {
                    $leftNum = intVal(strrev($m[0][0]));
                    $leftPos = $m[0][1];
                    $sum = $pairToExplode[0] + $leftNum;
                    //$message .= " | Left : ".$pairToExplode[0]." + ".$leftNum." = ".$sum;
                    $str = substr_replace($str, $sum, $i - $leftPos - strlen($leftNum), strlen($leftNum));
                }

                //dump("=> Explode : ".$str.$message);
                $found = true;
                break;
            }
        }

        return [
            'done' => $found,
            'pair' => $str,
        ];
    }


    protected function split($str)
    {
        $found = false;

        preg_match_all('!\d+!', $str, $match);
        foreach($match[0] as $value)
        {
            if(bccomp($value, '9', 0) === 1)
            {
                $half = $value / 2;
                $valueLeft = intVal(floor($half));
                $valueRight = intVal(ceil($half));
                $newVal = '['.$valueLeft.','.$valueRight.']';
                
                $pos = strpos($str, $value);
                $str = substr_replace($str, $newVal, $pos, strlen((string)$value));

                //dump("=> Split   : ".$str);
                $found = true;
                break;
            }
        }

        return [
            'done' => $found,
            'pair' => $str,
        ];
    }

    protected function sum($num1, $num2)
    {
        $pair = '['.$num1.','.$num2.']';
        return $this->reduce($pair);
    }

    protected function sumAll($nums)
    {
        $sum = $nums[0];
        for($i=1; $i<count($nums); ++$i)
        {
            $sum = $this->sum($sum, $nums[$i]);
        }
        return $sum;
    }

    protected function magnitude($pair)
    {
        $v1 = $pair[0];
        $v2 = $pair[1];
        $v1 = 3 * (is_array($v1) ? $this->magnitude($v1) : $v1);
        $v2 = 2 * (is_array($v2) ? $this->magnitude($v2) : $v2);
        $v = $v1 + $v2;
        return $v;
    }
}
