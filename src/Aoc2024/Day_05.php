<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_05 extends Aoc
{
    protected $rulesBefore;
    protected $updates;

    protected function init()
    {
        $this->rulesBefore = [];
        $this->updates = [];

        $switch = false;
        foreach($this->lines as $line)
        {
            if(!$line){
                $switch = true;
            }else if($switch){
                $this->updates[] = explode(',', $line);
            }else{
                $rule = explode('|', $line);
                if(!isset($this->rulesBefore[$rule[1]])){
                    $this->rulesBefore[$rule[1]] = [];
                }
                $this->rulesBefore[$rule[1]][] = $rule[0];
            }
        }
    }

    protected function runPart1()
    {
        $sum = 0;
        foreach($this->updates as $update)
        {
            $valid = $this->isUpdateValid($update);
            if($valid)
            {
                $middle = $update[floor(count($update)/2)];
                $sum += $middle;
            }
        }
        return $sum;
    }

    protected function runPart2()
    {
        $sum = 0;
        foreach($this->updates as $update)
        {
            $valid = $this->isUpdateValid($update);
            if(!$valid)
            {
                $update = $this->correctUpdate($update);
                $middle = $update[floor(count($update)/2)];
                $sum += $middle;
            }
        }
        return $sum;
    }

    protected function isUpdateValid($update)
    {
        foreach($update as $i => $value)
        {
            if(isset($this->rulesBefore[$value]))
            {
                $valuesBefore = $this->rulesBefore[$value];
                for($j=$i+1; $j<count($update); ++$j)
                {
                    if(in_array($update[$j], $valuesBefore)){
                        return false;
                    }
                }
            }
        }
        return true;
    }

    protected function correctUpdate($update)
    {
        for($i=0; $i<count($update); ++$i)
        {
            $value = $update[$i];
            $shouldBreak = false;
            if(isset($this->rulesBefore[$value]))
            {
                $valuesBefore = $this->rulesBefore[$value];
                for($j=$i+1; $j<count($update); ++$j)
                {
                    if(in_array($update[$j], $valuesBefore))
                    {
                        $movedValue = $update[$j];
                        array_splice($update, $j, 1);
                        array_splice($update, $i, 0, $movedValue);
                        $update = $this->correctUpdate($update);

                        $shouldBreak = true;
                        break;
                    }
                }
            }
            if($shouldBreak){
                break;
            }
        }
        return $update;
    }
}
