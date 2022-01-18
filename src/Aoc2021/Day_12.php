<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_12 extends Aoc
{
    protected $connections = [];
    protected $paths = [];

    protected function init()
    {
        $this->lines = array_filter($this->lines);
        $this->connections = [];
        $this->paths = [];

        foreach($this->lines as $line)
        {
            $parts = explode('-', $line);

            if(!isset($this->connections[$parts[0]])){
                $this->connections[$parts[0]] = [];
            }
            if(!isset($this->connections[$parts[1]])){
                $this->connections[$parts[1]] = [];
            }
            if($parts[1] !== 'start' && $parts[0] !== 'end'){
                $this->connections[$parts[0]][] = $parts[1];    
            }
            if($parts[0] !== 'start' && $parts[1] !== 'end'){
                $this->connections[$parts[1]][] = $parts[0];
            }
        }
    }

    protected function runPart1()
    {
        $this->visitPaths('start', ['start'], false);
        return count($this->paths);
    }

    protected function runPart2()
    {
        $this->visitPaths('start', ['start'], true);
        return count($this->paths);
    }

    protected function visitPaths($cave, $pathToCopy, $allowTwice)
    {
        foreach($this->connections[$cave] as $nextCave)
        {
            $path = $pathToCopy;

            if($nextCave === 'end')
            {
                $path[] = 'end';
                $this->paths[] = $path;
            }
            else if(ctype_upper($nextCave))
            {
                $path[] = $nextCave;
                $this->visitPaths($nextCave, $path, $allowTwice);
            }
            else
            {
                if(!in_array($nextCave, $path))
                {
                    $path[] = $nextCave;
                    $this->visitPaths($nextCave, $path, $allowTwice);
                }
                else if($allowTwice)
                {
                    $path[] = $nextCave;
                    $this->visitPaths($nextCave, $path, false);
                }
            }
        }
    }
}
