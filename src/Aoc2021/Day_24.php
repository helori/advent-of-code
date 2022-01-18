<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_24 extends Aoc
{
    // ------------------------------------------------
    // Method : 
    // For each input digit, compile the instructions
    // to obtain formulas for w, x, y, and z.
    //
    // After digit 0 :
    // $w = $d[0];
    // $x = 1;
    // $y = $d[0];
    // $z = $d[0];
    //
    // etc..
    //
    // After digit 9 :
    // $w = $d[9];
    // $x = $p3;
    // $y = ($d[9] + 12) * $p3;
    // $z = $z9;
    // With :
    // $z1 = $d[0] * 26 + $d[1] + 12;
    // $z2 = $z1 * 26 + $d[2] + 14;
    // $p1 = ($d[3]-2 == $d[4]) ? 0 : 1;
    // $z4 = $z2 * (1 + 25 * $p1) + ($d[4] + 3) * $p1;
    // $z5 = $z4 * 26 + $d[5] + 15;
    // $p2 = ($d[6] - 4 == $d[7]) ? 0 : 1;
    // $z7 = $z5 * (25 * $p2 + 1) + ($d[7] + 12) * $p2;
    // $p3 = ($d[8] - 8 == $d[9]) ? 0 : 1; 
    // $z9 = $z7 * (25 * $p3 + 1) + ($d[9] + 12) * $p3;
    // 
    // At this point, we notice there are terms (p1, p2, p3) that can be equal to 0 or 1.
    // We assume that their value IS zero to build the next formulas,
    // and we keep the conditions that should be met so their value actually IS zero :
    //
    // p1 = 0 if : d4 = d3 - 2
    // p2 = 0 if : d7 = d6 - 4
    // p3 = 0 if : d9 = d8 - 8
    //
    // At the end, we have all the conditions used below to build valid numbers.
    // ------------------------------------------------

    protected function init()
    {
        $this->lines = array_filter($this->lines);
    }

    protected function runPart1()
    {
        $digits = array_fill(0, 14, null);

        $digits[3] = 9;
        $digits[6] = 9;
        $digits[8] = 9;
        $digits[10] = 9;
        $digits[11] = 9;
        $digits[12] = 9;
        $digits[0] = 9;

        $digits[4] = $digits[3] - 2;
        $digits[7] = $digits[6] - 4;
        $digits[9] = $digits[8] - 8;
        $digits[5] = $digits[10] - 6;
        $digits[2] = $digits[11] - 7;
        $digits[1] = $digits[12] - 8;
        $digits[13] = $digits[0] - 6;

        return intVal(implode('', $digits));
    }

    protected function runPart2()
    {
        $digits = array_fill(0, 14, null);

        $digits[4] = 1;
        $digits[7] = 1;
        $digits[9] = 1;
        $digits[5] = 1;
        $digits[2] = 1;
        $digits[1] = 1;
        $digits[13] = 1;

        $digits[3] = $digits[4] + 2;
        $digits[6] = $digits[7] + 4;
        $digits[8] = $digits[9] + 8;
        $digits[10] = $digits[5] + 6;
        $digits[11] = $digits[2] + 7;
        $digits[12] = $digits[1] + 8;
        $digits[0] = $digits[13] + 6;

        return intVal(implode('', $digits));
    }
}
