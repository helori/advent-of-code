<?php

namespace Aoc;

use Symfony\Component\VarDumper\VarDumper;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Carbon\Carbon;

abstract class Aoc
{
    protected $lines = [];

    abstract protected function init();
    abstract protected function runPart1();
    abstract protected function runPart2();

    public function __construct()
    {
        $filename = Str::after(static::class, 'Aoc\\');
        $this->lines = array_filter($this->fileLines($filename.'.txt'));
        $this->init();
    }

    public function run()
    {
        $start = Carbon::now();
        $result1 = $this->runPart1();
        $secondsPart1 = $start->floatDiffInSeconds();

        $start = Carbon::now();
        $result2 = $this->runPart2();
        $secondsPart2 = $start->floatDiffInSeconds();

        return [
            'result_part1' => $result1,
            'result_part2' => $result2,
            'seconds_part1' => $secondsPart1,
            'seconds_part2' => $secondsPart2,
        ];
    }

    protected function fileLines($filename)
    {
        $filepath = __DIR__.'/../files/'.$filename;
        
        if(!file_exists($filepath)){
            $this->dd("Missing file : ".$filepath);
        }

        $content = trim(file_get_contents($filepath));
        if(empty($content)){
            $this->dd("Empty file : ".$filepath);
        }

        $content = fopen($filepath, 'r');
        $values = [];

        while(!feof($content))
        {
            $values[] = trim(fgets($content));
        }
        return $values;
    }

    protected function toInts(array $values)
    {
        return array_map(function($v){ return intVal($v); }, $values);
    }

    protected function increment(array &$values, int $inc = 1)
    {
        return array_map(function($v) use($inc) { return $v + $inc; }, $values);
    }

    protected function matrixRows(&$matrix)
    {
        return count($matrix);
    }

    protected function matrixCols(&$matrix)
    {
        return count($matrix[0]);
    }

    protected function readMatrix(&$matrix, $callback)
    {
        $rows = $this->matrixRows($matrix);
        $cols = $this->matrixCols($matrix);

        for($r=0; $r<$rows; ++$r)
        {
            for($c=0; $c<$cols; ++$c)
            {
                $callback($matrix[$r][$c], $r, $c, $rows, $cols);
            }
        }
    }

    protected function dump($value)
    {
        VarDumper::dump($value);
    }

    protected function dd($value)
    {
        VarDumper::dump($value);
        exit;
    }
}
