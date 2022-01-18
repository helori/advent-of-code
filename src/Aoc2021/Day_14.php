<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_14 extends Aoc
{
    protected $template = null;
    protected $rules = [];
    protected $rule2 = [];

    protected function init()
    {
        $this->lines = array_filter($this->lines);
        $this->template = $this->lines[0];
        $this->rules = [];
        $this->rules2 = [];

        foreach($this->lines as $i => $line)
        {
            if($i > 0){
                $parts = explode(' -> ', $line);
                $this->rules[$parts[0]] = $parts[1];
                $this->rules2[$parts[0]] = substr_replace($parts[0], $parts[1], 1, 0);
            }
        }
    }

    protected function runPart1()
    {
        $steps = 10;
        for($step=0; $step<$steps; ++$step)
        {
            $this->applyStep1();
            //echo "Step ".($step + 1)." : ".$this->template."\n";
        }

        //echo "Template  : ".$this->template."\n";
        //echo "Length  : ".strlen($this->template)."\n";

        $letters = str_split($this->template);
        $counts = array_count_values($letters);
        //$this->dump($counts);
        sort($counts);
        $counts = array_values($counts);
        return $counts[count($counts) - 1] - $counts[0];
    }

    protected function applyStep1()
    {
        $newTemplate = '';
        for($i=0; $i<strlen($this->template)-1; ++$i)
        {
            $pair = substr($this->template, $i, 2);
            $insert = $this->rules[$pair];
            $newTemplate .= substr_replace($pair, $insert, 1, 1);
        }
        $this->template = $newTemplate.substr($this->template, -1);
    }



    protected function runPart2()
    {
        $pairs = [];
        for($i=0; $i<strlen($this->template)-1; ++$i)
        {
            $pair = substr($this->template, $i, 2);
            $this->incrementPair($pairs, $pair, 1);
        }

        $steps = 40;
        for($step=0; $step<$steps; ++$step)
        {
            $this->duplicatePairs($pairs);
        }

        //$this->dump($pairs);
        //echo "Length  : ".array_sum($pairs)."\n";

        $counts = [];

        foreach($pairs as $pair => $count)
        {
            $letter1 = substr($pair, 0, 1);
            $letter2 = substr($pair, 1, 1);

            if(!isset($counts[$letter1])){
                $counts[$letter1] = 0;
            }
            if(!isset($counts[$letter2])){
                $counts[$letter2] = 0;
            }

            $counts[$letter1] += $count;
            //$counts[$letter2] += $count;
        }

        $counts[substr($this->template, -1)] += 1;

        //$this->dump($counts);

        sort($counts);
        $counts = array_values($counts);
        return $counts[count($counts) - 1] - $counts[0];
    }

    protected function duplicatePairs(&$pairs)
    {
        foreach($pairs as $pair => $count)
        {
            $letter = $this->rules[$pair];
            $pair1 = substr($pair, 0, 1).$letter;
            $pair2 = $letter.substr($pair, 1, 1);

            $pairs[$pair] = max(0, $pairs[$pair] - $count);
            $this->incrementPair($pairs, $pair1, $count);
            $this->incrementPair($pairs, $pair2, $count);
        }
    }

    protected function incrementPair(&$pairs, $pair, $count)
    {
        if(!isset($pairs[$pair])){
            $pairs[$pair] = 0;
        }
        $pairs[$pair] += $count;
    }
}
