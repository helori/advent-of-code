<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_05 extends Aoc
{
    protected $seeds = [];
    protected $parts = [];

    protected function init()
    {
        $lines = $this->lines;
        $this->seeds = $this->toInts(explode(' ', trim(Str::after($lines[0], ': '))));
        
        array_splice($lines, 0, 2);
        
        $this->parts = [];
        $part = [];
        foreach($lines as $line)
        {
            $line = trim($line);
            if(Str::contains($line, 'map:'))
            {
                if(count($part) > 0){
                    $this->parts[] = $part;
                }
                $part = [];
            }
            else if($line)
            {
                $part[] = $this->toInts(explode(' ', $line));
            }
        }
        if(count($part) > 0){
            $this->parts[] = $part;
        }
    }

    protected function runPart1()
    {
        $destinations = [];

        foreach($this->seeds as $seed)
        {
            $current = $seed;
            foreach($this->parts as $part)
            {
                foreach($part as $lineData)
                {
                    $targetStart = $lineData[0];
                    $sourceStart = $lineData[1];
                    $range = $lineData[2];

                    if(($current >= $sourceStart) && ($current < ($sourceStart + $range)))
                    {
                        $current = $targetStart + ($current - $sourceStart);
                        break;
                    }
                }
            }
            $destinations[$seed] = $current;
        }

        return min(array_values($destinations));
    }

    protected function runPart2()
    {
        $destinations = [];

        for($i=0; $i<count($this->seeds); $i+=2)
        {
            $ranges = [
                [ $this->seeds[$i], $this->seeds[$i] + $this->seeds[$i+1] - 1 ],
            ];

            foreach($this->parts as $part)
            {
                usort($ranges, function($a, $b){
                    return ($a[0] == $b[0]) ? 0 : ($a[0] < $b[0] ? -1 : 1);
                });

                usort($part, function($a, $b){
                    return ($a[1] == $b[1]) ? 0 : ($a[1] < $b[1] ? -1 : 1);
                });

                $targetRanges = [];

                foreach($ranges as &$range)
                {
                    $subPart = array_values(array_filter($part, function($lineData) use($range)
                    {
                        $start = $lineData[1];
                        $length = $lineData[2];
                        return (($start + $length - 1) >= $range[0]) && ($start <= $range[1]);
                    }));

                    $cursor = $range[0];
                    
                    foreach($subPart as $lineData)
                    {
                        if($cursor < $lineData[1])
                        {
                            $targetRanges[] = [ $cursor, $lineData[1] - 1 ];
                            $cursor = $lineData[1];
                        }

                        if($cursor >= $lineData[1] && ($cursor <= $lineData[1] + $lineData[2]))
                        {
                            $s1 = max($lineData[1], $cursor);
                            $s2 = min($range[1], $lineData[1] + $lineData[2] - 1);

                            $t1 = $lineData[0] + ($s1 - $lineData[1]);
                            $t2 = $lineData[0] + ($s2 - $lineData[1]);

                            $targetRanges[] = [ $t1, $t2 ];

                            $cursor = $s2 + 1;
                        }
                    }

                    if($cursor <= $range[1])
                    {
                        $targetRanges[] = [ $cursor, $range[1] ];
                    }
                }

                $ranges = $targetRanges;
            }
            $destinations[] = $ranges;
        }

        $min = null;
        foreach($destinations as $seedRanges)
        {
            usort($seedRanges, function($a, $b){
                return ($a[0] == $b[0]) ? 0 : ($a[0] < $b[0] ? -1 : 1);
            });
            $min = is_null($min) ? $seedRanges[0][0] : min($min, $seedRanges[0][0]);
        }
        return $min;
    }
}
