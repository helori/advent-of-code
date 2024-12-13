<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_13 extends Aoc
{
    protected $machines;

    protected function init()
    {
        
    }

    protected function runPart1()
    {
        $this->readMachines(0);
        return $this->getCost();
    }

    protected function runPart2()
    {
        $this->readMachines(10000000000000);
        return $this->getCost();
    }

    protected function readMachines($offset)
    {
        $this->machines = [];
        $i = 0;

        while($i<count($this->lines))
        {
            $this->machines[] = [
                'A' => [
                    'x' => intVal(Str::before(Str::after($this->lines[$i], 'Button A: X+'), ', ')),
                    'y' => intVal(Str::after($this->lines[$i], 'Y+')),
                ],
                'B' => [
                    'x' => intVal(Str::before(Str::after($this->lines[$i+1], 'Button B: X+'), ', ')),
                    'y' => intVal(Str::after($this->lines[$i+1], 'Y+')),
                ],
                'P' => [
                    'x' => $offset + intVal(Str::before(Str::after($this->lines[$i+2], 'Prize: X='), ', ')),
                    'y' => $offset + intVal(Str::after($this->lines[$i+2], 'Y=')),
                ],
            ];
            $i += 4;
        }
    }

    protected function getCost()
    {
        $cost = 0;
        $cost_a = 3;
        $cost_b = 1;

        foreach($this->machines as $machine)
        {
            $result = $this->getPrize($machine);
            //dump($result);
            if($result['win'])
            {
                $cost += $cost_a * round($result['count_a']) + $cost_b * round($result['count_b']);
            }
        }
        return $cost;
    }

    protected function getPrize($machine)
    {
        $xa = $machine['A']['x'];
        $ya = $machine['A']['y'];

        $xb = $machine['B']['x'];
        $yb = $machine['B']['y'];

        $x = $machine['P']['x'];
        $y = $machine['P']['y'];

        bcscale(20); // Very important for part 2

        $nb = bcdiv(bcsub($y, bcmul($ya, bcdiv($x, $xa))), bcsub($yb, bcmul($ya, bcdiv($xb, $xa))));
        $na = bcdiv(bcsub($x, bcmul($nb, $xb)), $xa);

        return [
            'machine' => $machine,
            'win' => $na >= 0 && $nb >= 0 && $this->isdecimal($na) && $this->isdecimal($nb),
            'count_a' => $na,
            'count_b' => $nb,
        ];
    }

    protected function isdecimal($value)
    {   
        return abs(round($value) - floatVal($value)) < 0.0001;
    }
}
