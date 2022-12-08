<?php

namespace Aoc\Aoc2022;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_05 extends Aoc
{
    protected $trimLines = false;
    protected $stacks = [];
    protected $commands = [];

    protected function init()
    {
        $stacks = [];
        for($i=0; $i<8; ++$i)
        {
            $line = trim($this->lines[$i], "\n");
            $crates = explode('-', chunk_split($line, 4, '-'));
            unset($crates[9]);
            
            $crates = array_map(function($crate){
                $crate = trim($crate, "[] ");
                return strlen($crate) > 0 ? $crate : ' ';
            }, $crates);
            
            $stacks[] = $crates;
        }
        $stacks = $this->matrixCols($stacks);
        $stacks = array_map(function($stack){
            for($i=7; $i>=0; $i--){
                if($stack[$i] === ' '){
                    unset($stack[$i]);
                }
            }
            return $stack;
        }, $stacks);

        $this->stacks = $stacks;        
        //$this->renderMatrix($stacks, '');
        //exit;
        
        $this->commands = [];
        for($i=10; $i<count($this->lines); ++$i)
        {
            $instruction = trim($this->lines[$i]);
            if($instruction)
            {
                $this->commands[] = [
                    'n' => intVal(Str::before(Str::after($instruction, 'move'), 'from')),
                    'f' => intVal(Str::before(Str::after($instruction, 'from'), 'to')) - 1,
                    't' => intVal(Str::after($instruction, 'to')) - 1,
                ];
            }
        }
    }

    protected function runPart1()
    {
        foreach($this->commands as $command){
            $this->execute($command);
        }

        $crates = '';
        foreach($this->stacks as $stack){
            $crates .= $stack[0];
        }

        return $crates;
    }

    protected function execute($command)
    {
        for($i=0; $i<$command['n']; ++$i)
        {
            $crate = array_shift($this->stacks[$command['f']]);
            array_unshift($this->stacks[$command['t']], $crate);
        }
    }

    protected function runPart2()
    {
        foreach($this->commands as $command){
            $this->execute2($command);
        }

        $crates = '';
        foreach($this->stacks as $stack){
            $crates .= $stack[0];
        }

        return $crates;
    }

    protected function execute2($command)
    {
        $crates = [];
        for($i=0; $i<$command['n']; ++$i)
        {
            $crates[] = array_shift($this->stacks[$command['f']]);
        }

        $crates = array_reverse($crates);
        foreach($crates as $crate)
        {
            array_unshift($this->stacks[$command['t']], $crate);
        }
    }
}
