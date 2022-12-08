<?php

namespace Aoc;

use Symfony\Component\VarDumper\VarDumper;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Carbon\Carbon;

abstract class Aoc
{
    protected $year = null;
    protected $lines = [];
    protected $trimLines = true;

    abstract protected function init();
    abstract protected function runPart1();
    abstract protected function runPart2();

    public function __construct()
    {
        $this->year = $this->getYear();
        $filename = Str::afterLast(static::class, '\\');
        $this->lines = $this->fileLines($filename.'.txt');
    }

    public function getYear()
    {
        $object = new \ReflectionObject($this);
        $method = $object->getMethod('init');
        $declaringClass = $method->getDeclaringClass();
        $filename = $declaringClass->getFilename();
        return intVal(Str::afterLast(dirname($filename), '/Aoc'));
    }

    public function run()
    {
        $start = Carbon::now();
        $this->init();
        $result1 = $this->runPart1();
        $secondsPart1 = $start->floatDiffInSeconds();

        $start = Carbon::now();
        $this->init();
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
        $filepath = dirname(__DIR__).'/files/Aoc'.$this->year.'/'.$filename;
        
        if(!file_exists($filepath)){
            dd("Missing file : ".$filepath);
        }

        $content = trim(file_get_contents($filepath));
        if(empty($content)){
            dd("Empty file : ".$filepath);
        }

        $content = fopen($filepath, 'r');
        $values = [];

        while(!feof($content))
        {
            $values[] = $this->trimLines ? trim(fgets($content)) : fgets($content);
        }
        return $values;
    }

    protected function toInts(array $values)
    {
        return array_map(function($v){ return intVal($v); }, $values);
    }

    protected function increment(array &$values, int $inc = 1)
    {
        return array_map(function($v) use($inc) { 
            return $v + $inc; 
        }, $values);
    }

    protected function arrayMax(array $array, $key)
    {
        $max = null;
        foreach($array as $item){
            $max = is_null($max) ? $item[$key] : max($max, $item[$key]);
        }
        return $max;
    }

    protected function matrixNumRows($matrix)
    {
        return count($matrix);
    }

    protected function matrixNumCols($matrix)
    {
        return count($matrix[0]);
    }

    protected function matrixCol($matrix, $colIdx)
    {
        $col = [];
        for($r=0; $r<count($matrix); $r++){
            $col[] = $matrix[$r][$colIdx];
        }
        return $col;
    }

    protected function matrixCols($matrix)
    {
        $cols = [];
        $numCols = $this->matrixNumCols($matrix);
        $numRows = $this->matrixNumRows($matrix);

        for($c=0; $c<$numCols; $c++)
        {
            $col = [];
            for($r=0; $r<$numRows; $r++){
                $col[] = $matrix[$r][$c];
            }
            $cols[] = $col;
        }

        return $cols;
    }

    protected function readMatrix(&$matrix, $callback)
    {
        $rows = $this->matrixNumRows($matrix);
        $cols = $this->matrixNumCols($matrix);

        for($r=0; $r<$rows; ++$r)
        {
            for($c=0; $c<$cols; ++$c)
            {
                $callback($matrix[$r][$c], $r, $c, $rows, $cols);
            }
        }
    }

    protected function matrixAt(&$matrix, $r, $c)
    {
        if($this->matrixExistsAt($matrix, $r, $c))
        {
            return $matrix[$r][$c];
        }
        return null;
    }

    protected function matrixExistsAt(&$matrix, $r, $c)
    {
        $rows = $this->matrixNumRows($matrix);
        $cols = $this->matrixNumCols($matrix);
        return ($r >= 0 && ($r < $rows) && $c >= 0 && ($c < $cols));
    }

    protected function renderMatrix(&$matrix, $separator = '')
    {
        $rows = $this->matrixNumRows($matrix);
        $cols = $this->matrixNumCols($matrix);

        for($r=0; $r<$rows; ++$r)
        {
            $lineStr = implode($separator, $matrix[$r]);
            echo $lineStr."\n";
        }
    }

    protected function renderLines(&$lines)
    {
        $rows = count($lines);
        for($r=0; $r<$rows; ++$r)
        {
            echo $lines[$r]."\n";
        }
    }
}
