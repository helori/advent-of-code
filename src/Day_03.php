<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_03 extends Aoc
{
    protected function init()
    {
        $this->lines = array_filter($this->lines);
    }

    protected function runPart1()
    {
        $matrix = $this->lines;
        foreach($matrix as $i => $line){
            $matrix[$i] = $this->toInts(str_split(trim($line)));
        }

        $numCols = $this->matrixCols($matrix);

        $g = '';
        $e = '';

        for($i = 0; $i<$numCols; ++$i)
        {
            $column = $this->matrixCol($matrix, $i);

            $num1 = array_sum($column);
            $more1 = ($num1 >= count($column)/2);

            $g .= $more1 ? '1' : '0';
            $e .= $more1 ? '0' : '1';
        }

        return bindec($g) * bindec($e);
    }

    protected function runPart2()
    {
        $values = $this->lines;
        $length = strlen($values[0]);

        $o = '';
        $s = '';
        $serieO = $values;
        $serieS = $values;

        for($i = 0; $i<$length; ++$i)
        {
            if(count($serieO) > 1)
            {
                $num1 = 0;
                foreach($serieO as $value)
                {
                    if($value[$i] === '1'){
                        $num1++;
                    }
                }

                $mostPresent = ($num1 >= count($serieO)/2) ? '1' : '0';
                $o .= $mostPresent;

                foreach($serieO as $j => $value)
                {
                    if($value[$i] !== $mostPresent){
                        unset($serieO[$j]);
                    }
                }
                $serieO = array_values($serieO);

                if(count($serieO) === 1)
                {
                    $o = $serieO[0];
                }
            }

            // ------------------

            if(count($serieS) > 1)
            {
                $num0 = 0;
                foreach($serieS as $value)
                {
                    if($value[$i] === '0'){
                        $num0++;
                    }
                }
                $lessPresent = ($num0 <= count($serieS)/2) ? '0' : '1';

                foreach($serieS as $j => $value)
                {
                    if($value[$i] !== $lessPresent){
                        unset($serieS[$j]);
                    }
                }
                $serieS = array_values($serieS);

                if(count($serieS) === 1)
                {
                    $s = $serieS[0];
                }
            }
        }
        
        return bindec($o) * bindec($s);
    }
}
