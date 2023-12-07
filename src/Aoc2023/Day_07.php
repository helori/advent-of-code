<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_07 extends Aoc
{
    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $hands = [];
        $points = array_flip(['2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A']);

        foreach($this->lines as $line)
        {
            $parts = explode(' ', $line);
            $cards = str_split($parts[0]);
            
            $cardsPoints = array_map(function($card) use($points) {
                return $points[$card];
            }, $cards);

            $dup = array_count_values($cardsPoints);
            $poker = array_count_values($dup);
            $score = $this->pokerScore($poker);

            $hands[] = [
                'bid' => intVal($parts[1]),
                'cards' => $cards,
                'cards_points' => $cardsPoints,
                'dup' => $dup,
                'poker' => $poker,
                'score' => $score,
            ];
        }

        // Sort hands
        usort($hands, function($h1, $h2)
        {
            $s1 = $h1['score'];
            $s2 = $h2['score'];

            if($s1 !== $s2)
            {
                return ($s1 < $s2) ? -1 : 1;
            }

            // Compare first strongest
            for($i=0; $i<5; ++$i)
            {
                $v1 = $h1['cards_points'][$i];
                $v2 = $h2['cards_points'][$i];
                if($v1 !== $v2){
                    return ($v1 < $v2) ? -1 : 1;
                }
            }
            return 0;
        });

        $scores = [];
        foreach($hands as $rank => $hand)
        {
            $scores[] = ($rank + 1) * $hand['bid'];
        }

        return array_sum($scores);
    }

    protected function pokerScore($handPoker)
    {
        if(isset($handPoker['5'])) return 6;
        else if(isset($handPoker['4'])) return 5;
        else if(isset($handPoker['3']) && isset($handPoker['2'])) return 4;
        else if(isset($handPoker['3'])) return 3;
        else if(isset($handPoker['2']) && $handPoker['2'] === 2) return 2;
        else if(isset($handPoker['2'])) return 1;
        else return 0;
    }

    protected function runPart2()
    {
        $hands = [];
        $points = array_flip(['J', '2', '3', '4', '5', '6', '7', '8', '9', 'T', 'Q', 'K', 'A']);

        foreach($this->lines as $line)
        {
            $parts = explode(' ', $line);
            $cards = str_split($parts[0]);
            
            $cardsPoints = array_map(function($card) use($points) {
                return $points[$card];
            }, $cards);

            $dup = array_count_values($cardsPoints);
            
            // check if has joker :
            if(isset($dup['0']))
            {
                $numJokers = $dup['0'];
                if($numJokers === 5)
                {
                    $bestCardValue = (string)(count($points) - 1);
                    $dup = [
                        $bestCardValue => 5,
                    ];
                }
                else
                {
                    // Turn jokers to the most recurrent card in hand
                    unset($dup['0']);
                    $maxKey = array_keys($dup, max($dup))[0];
                    $dup[$maxKey] += $numJokers;
                }
            }

            // Count the number of occurences (5 cards, 4 cards, 3 cards, ...)
            $poker = array_count_values($dup);
            $score = $this->pokerScore($poker);

            $hands[] = [
                'bid' => intVal($parts[1]),
                'cards' => $cards,
                'cards_points' => $cardsPoints,
                'dup' => $dup,
                'poker' => $poker,
                'score' => $score,
            ];
        }

        // Sort hands
        usort($hands, function($h1, $h2)
        {
            $s1 = $h1['score'];
            $s2 = $h2['score'];

            if($s1 !== $s2)
            {
                return ($s1 < $s2) ? -1 : 1;
            }

            // Compare first strongest
            for($i=0; $i<5; ++$i)
            {
                $v1 = $h1['cards_points'][$i];
                $v2 = $h2['cards_points'][$i];
                if($v1 !== $v2){
                    return ($v1 < $v2) ? -1 : 1;
                }
            }
            return 0;
        });

        $scores = [];
        foreach($hands as $rank => $hand)
        {
            $scores[] = ($rank + 1) * $hand['bid'];
        }

        return array_sum($scores);
    }
}
