<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_09 extends Aoc
{
    protected $values = [];

    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $values = $this->explodeByIds();
        $arranged = $this->arrangeOrderByDigit($values);
        return $this->checksum($arranged);
    }

    protected function runPart2()
    {
        $values = $this->explodeByIds();
        $arranged = $this->arrangeOrderByFile($values);
        return $this->checksum($arranged);
    }

    protected function explodeByIds()
    {
        $map = str_split($this->lines[0]);
        $values = [];
        
        foreach($map as $i => $digit)
        {
            if($i % 2 === 1)
            {
                $values = array_merge($values, array_fill(0, intVal($digit), '.'));
                //$str .= str_repeat('.', intVal($digit));
            }
            else
            {
                $values = array_merge($values, array_fill(0, intVal($digit), $i/2));
                //$str .= str_repeat($i/2, intVal($digit));
            }
        }
        return $values;
    }

    protected function arrangeOrderByDigit(array $values)
    {
        $j = count($values) - 1;

        for($i=0; $i<count($values); $i++)
        {
            if($i>=$j){
                break;
            }
            if($values[$i] === '.')
            {
                while($values[$j] === '.'){
                    $j--;
                }
                if($j > $i)
                {
                    $values[$i] = $values[$j];
                    $values[$j] = '.';
                }
            }
        }
        return $values;
    }

    protected function arrangeOrderByFile(array $values)
    {
        //dump(join($values));
        $j = count($values) - 1;

        while($j > 0)
        {
            // Find last file
            while($values[$j] === '.'){ $j--; }
            $lastId = $values[$j];
            $endJ = $j;
            while($values[$j] === $lastId) { $j--; }
            $startJ = $j+1;

            // Find available space
            $i = 0;
            $found = false;
            $available = 0;
            while(!$found && ($i < $startJ))
            {
                if($values[$i] !== '.')
                {
                    $available = 0;
                }
                else
                {
                    $available++;
                    if($available === ($endJ - $startJ + 1))
                    {
                        $found = true;
                        $startI = $i - $available + 1;
                        $endI = $i;
                    }
                }
                ++$i;
            }
            
            if($found)
            {
                for($k=$startI; $k<=$endI; $k++)
                {
                    $values[$k] = $lastId;
                }
                for($k=$startJ; $k<=$endJ; $k++)
                {
                    $values[$k] = '.';
                }
                //dump(join($values));
            }
        }
        return $values;
    }

    protected function checksum(array $values)
    {
        $checksum = 0;
        foreach($values as $i => $value)
        {
            if($value !== '.'){
                $checksum += ($i * intVal($value));
            }
        }
        return $checksum;
    }
}
