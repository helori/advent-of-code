<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_12 extends Aoc
{

    protected $items = [];

    protected function init()
    {
        $this->items = [];
        foreach($this->lines as $line)
        {
            $part1 = Str::before($line, ' ');
            $part2 = Str::after($line, ' ');

            $data = array_filter(explode('.', preg_replace('/\.+/', '.', $part1)));

            $data = array_map(function($d){
                return [
                    'value' => $d,
                    'length' => strlen($d),
                    'variable' => substr_count($d, '?'),
                ];
            }, $data);

            $this->items[] = [
                'data' => $data,
                'dups' => explode(',', $part2),
            ];
        }
    }

    protected function runPart1()
    {
        // Nombre de k possibles dans un groupe de N
        $r = gmp_binomial($n, $k);

        // Nombre de k possibles collés dans un groupe de N
        $r = $n - $k + 1;
        
        foreach($this->items as $item)
        {
            
        }
        return 0;
    }

    protected function runPart2()
    {
        return 0;
    }
}
