<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_10 extends Aoc
{
    protected $chunks = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    protected function init()
    {
        $this->lines = array_filter($this->lines);
    }

    protected function runPart1()
    {
        $corrupted = $this->getAnalysis()['corrupted'];

        return array_sum(array_map(function($c){
            return $this->pointsForChar($c['found']);
        }, $corrupted));
    }

    protected function runPart2()
    {
        $incompletes = $this->getAnalysis()['incompletes'];

        $scores = [];
        foreach($incompletes as $line => $chars)
        {
            $score = 0;
            for($i=0; $i<strlen($chars); ++$i)
            {
                $score *= 5;
                $score += $this->completionPointsForChar($chars[$i]);
            }
            $scores[] = $score;
        }
        sort($scores);

        return $scores[intVal(floor(count($scores) / 2))];
    }

    protected function getAnalysis()
    {
        $corrupted = [];
        $incompletes = [];

        foreach($this->lines as $lineIdx => $line)
        {
            $opens = [];
            for($i=0; $i<strlen($line); ++$i)
            {
                $char = $line[$i];

                if(isset($this->chunks[$char]))
                {
                    $opens[] = $char;
                    $opens = array_values($opens);
                }
                else
                {
                    $lastIdx = count($opens) - 1;
                    if($opens[$lastIdx] === $this->openingCharFor($char)){
                        unset($opens[$lastIdx]);
                    }else{
                        $corrupted[$lineIdx] = [
                            'expected' => $this->closingCharFor($opens[$lastIdx]),
                            'found' => $char,
                        ];
                        break;
                    }
                }
            }
            if(!isset($corrupted[$lineIdx]) && !empty($opens))
            {
                $incompletes[$lineIdx] = '';
                for($j=0; $j<count($opens); ++$j){
                    $incompletes[$lineIdx] .= $this->closingCharFor($opens[$j]);
                }
            }
        }
        return [
            'corrupted' => $corrupted,
            'incompletes' => $incompletes,
        ];
    }

    protected function pointsForChar($char)
    {
        switch($char){
            case ')': return 3;
            case ']': return 57;
            case '}': return 1197;
            case '>': return 25137;
            default: return null;
        }
    }

    protected function completionPointsForChar($char)
    {
        switch($char){
            case ')': return 1;
            case ']': return 2;
            case '}': return 3;
            case '>': return 4;
            default: return null;
        }
    }

    protected function openingCharFor($char)
    {
        return array_flip($this->chunks)[$char];
    }

    protected function closingCharFor($char)
    {
        return $this->chunks[$char];
    }
}
