<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_04 extends Aoc
{
    protected $cards = [];

    protected function init()
    {
        $this->cards = [];

        foreach($this->lines as $i => $line)
        {
            $part1 = trim(Str::before(Str::after($line, ':'), '|'));
            $part2 = trim(Str::after($line, '|'));

            $numbers = $this->toInts(explode(' ', $this->singleWhitespaces($part1)));
            $winning = $this->toInts(explode(' ', $this->singleWhitespaces($part2)));

            $this->cards[] = [
                'numbers' => $numbers,
                'winning' => $winning,
            ];
        }
    }

    protected function runPart1()
    {
        $sum = 0;
        
        foreach($this->cards as $card)
        {
            $count = 0;
            foreach($card['numbers'] as $value)
            {
                $count += in_array($value, $card['winning']) ? 1 : 0;
            }
            $score = $count ? pow(2, $count - 1) : 0;
            $sum += $score;
        }
        
        return $sum;
    }

    protected function runPart2()
    {
        $counts = [];
        foreach($this->cards as $card)
        {
            $counts[] = 1;
        }

        foreach($this->cards as $i => $card)
        {
            $count = 0;
            foreach($card['numbers'] as $value)
            {
                $count += in_array($value, $card['winning']) ? 1 : 0;
            }
            
            for($j=1; $j<=$count; ++$j)
            {
                if($i + $j < count($this->cards)){
                    $counts[$i + $j] += $counts[$i];
                }
            }
        }
        
        return array_sum($counts);
    }
}
