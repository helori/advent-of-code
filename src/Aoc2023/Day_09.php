<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_09 extends Aoc
{
    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $sum = 0;

        foreach($this->lines as $line)
        {
            $values = $this->toInts(explode(' ', trim($line)));
            $sequences = [ $values ];

            $isNeg = $values[0] < 0;

            while(!$this->allZeros($values))
            {
                $values = $this->sequenceFrom($values);
                $sequences[] = $values;
            }

            $sequences[count($sequences) - 1][] = 0;

            for($i=count($sequences)-2; $i>=0; --$i)
            {
                $lastValNextSeq = $sequences[$i+1][count($sequences[$i+1]) - 1];
                $sequences[$i][] = $lastValNextSeq + $sequences[$i][count($sequences[$i]) - 1];
            }

            $extrapolated = $sequences[0][count($sequences[0]) - 1];

            if($isNeg){
                $this->renderMatrix($sequences, ' ');
                dump($extrapolated);
            }

            $sum += $extrapolated;
        }
        return $sum;
    }

    protected function allZeros($values)
    {
        $zeros = array_filter($values, function($v){
            return $v === 0;
        });
        return count($zeros) === count($values);
    }
    
    protected function sequenceFrom($values)
    {
        $seq = [];
        for($i=0; $i<count($values)-1; ++$i)
        {
            $seq[] = $values[$i+1] - $values[$i];
        }
        return $seq;
    }

    protected function runPart2()
    {
        $sum = 0;

        foreach($this->lines as $line)
        {
            $values = $this->toInts(explode(' ', trim($line)));
            $sequences = [ $values ];

            $isNeg = true; //$values[0] < 0;

            while(!$this->allZeros($values))
            {
                $values = $this->sequenceFrom($values);
                $sequences[] = $values;
            }

            $this->renderMatrix($sequences, ' ');

            array_unshift($sequences[count($sequences) - 1], 0);

            for($i=count($sequences)-2; $i>=0; --$i)
            {
                $firstValNextSeq = $sequences[$i+1][0];
                array_unshift($sequences[$i], $sequences[$i][0] - $firstValNextSeq);
            }

            $extrapolated = $sequences[0][0];

            if($isNeg){
                $this->renderMatrix($sequences, ' ');
                dump($extrapolated);
            }

            $sum += $extrapolated;
        }
        return $sum;
    }
}
