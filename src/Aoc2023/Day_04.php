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

            $matches = 0;
            foreach($numbers as $value)
            {
                $matches += in_array($value, $winning) ? 1 : 0;
            }

            $this->cards[] = [
                'numbers' => $numbers,
                'winning' => $winning,
                'matches' => $matches,
            ];
        }
    }

    protected function runPart1()
    {
        $sum = 0;
        foreach($this->cards as $card)
        {
            $sum += $card['matches'] ? pow(2, $card['matches'] - 1) : 0;
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
            for($j = 1; $j <= $card['matches']; ++$j)
            {
                if($i + $j < count($this->cards)){
                    $counts[$i + $j] += $counts[$i];
                }
            }
        }
        
        return array_sum($counts);
    }
}
