<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_03 extends Aoc
{
    protected $numbers = [];
    protected $symbols = [];

    protected function init()
    {
        $this->numbers = [];
        $this->symbols = [];

        foreach($this->lines as $y => $line)
        {
            $chars = str_split($line);
            $current = '';
            foreach($chars as $x => $char)
            {
                if(is_numeric($char)){
                    $current .= $char;
                }else{
                    if($current){
                        $this->numbers[] = [
                            'value' => intVal($current),
                            'length' => strlen($current),
                            'x1' => $x - strlen($current),
                            'x2' => $x - 1,
                            'y' => $y,
                        ];
                        $current = '';
                    }
                }
                if(!is_numeric($char) && ($char !== '.')){
                    $this->symbols[] = [$x, $y, $char];
                }
            }
            if($current){
                $this->numbers[] = [
                    'value' => intVal($current),
                    'length' => strlen($current),
                    'x1' => strlen($line) - 1 - strlen($current) + 1,
                    'x2' => strlen($line) - 1,
                    'y' => $y,
                ];
            }
        }
    }

    protected function runPart1()
    {
        $sum = 0;
        
        foreach($this->numbers as $number)
        {
            $x1 = $number['x1'];
            $x2 = $number['x2'];
            $y = $number['y'];

            foreach($this->symbols as $symbol)
            {   
                $sx = $symbol[0];
                $sy = $symbol[1];

                $yOk = ($sy >= ($y - 1)) && ($sy <= ($y + 1));
                $xOk = ($sx >= ($x1 - 1)) && ($sx <= ($x2 + 1));

                if($yOk && $xOk)
                {
                    $sum += $number['value'];
                    break;
                }
            }
        }
        
        return $sum;
    }

    protected function runPart2()
    {
        $sum = 0;

        $this->symbols = array_filter($this->symbols, function($symbol){
            return ($symbol[2] === '*');
        });

        foreach($this->symbols as $symbol)
        {
            $sx = $symbol[0];
            $sy = $symbol[1];
            $adjacents = [];

            foreach($this->numbers as $number)
            {   
                $x1 = $number['x1'];
                $x2 = $number['x2'];
                $y = $number['y'];

                $xOk = ($x1 >= $sx - 1) && ($x1 <= $sx + 1) || 
                        ($x2 >= $sx - 1) && ($x2 <= $sx + 1);

                $yOk = ($y >= ($sy - 1)) && ($y <= ($sy + 1));
                        
                if($xOk && $yOk)
                {
                    $adjacents[] = $number['value'];
                }
            }
            if(count($adjacents) === 2){
                $sum += $adjacents[0] * $adjacents[1];
            }
        }
        return $sum;
    }
}
