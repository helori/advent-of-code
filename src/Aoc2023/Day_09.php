<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_09 extends Aoc
{
    protected $parts = [];

    protected function init()
    {
        $this->parts = [];

        foreach($this->lines as $line)
        {
            $values = $this->toInts(explode(' ', trim($line)));
            $sequences = [ $values ];

            while(!$this->allZeros($values))
            {
                $seq = [];
                for($i=0; $i<count($values)-1; ++$i)
                {
                    $seq[] = $values[$i+1] - $values[$i];
                }
                $values = $seq;
                $sequences[] = $seq;
            }
            $this->parts[] = $sequences;
        }
    }

    protected function runPart1()
    {
        $sum = 0;

        foreach($this->parts as $s)
        {
            $s[count($s) - 1][] = 0;

            for($i=count($s)-2; $i>=0; --$i)
            {
                $lastValNextSeq = $s[$i+1][count($s[$i+1]) - 1];
                $s[$i][] = $lastValNextSeq + $s[$i][count($s[$i]) - 1];
            }

            $sum += $s[0][count($s[0]) - 1];
        }
        return $sum;
    }

    protected function runPart2()
    {
        $sum = 0;

        foreach($this->parts as $s)
        {
            array_unshift($s[count($s) - 1], 0);

            for($i=count($s)-2; $i>=0; --$i)
            {
                $firstValNextSeq = $s[$i+1][0];
                array_unshift($s[$i], $s[$i][0] - $firstValNextSeq);
            }

            $sum += $s[0][0];
        }
        return $sum;
    }

    protected function allZeros($values)
    {
        $counts = array_count_values($values);
        return isset($counts[0]) && ($counts[0] === count($values));
    }
}
