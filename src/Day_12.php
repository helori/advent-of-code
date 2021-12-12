<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_12 extends Aoc
{
    protected $connections = [];

    protected function init()
    {
        $this->lines = array_filter($this->lines);

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
        $paths = [];
        $this->visitPaths('start', $paths, ['start'], false);
        return count($paths);
    }

    protected function runPart2()
    {
        $paths = [];
        $this->visitPaths('start', $paths, ['start'], true);
        return count($paths);
    }

    protected function visitPaths($cave, &$paths, $pathToCopy, $allowTwice)
    {
        foreach($this->connections[$cave] as $nextCave)
        {
            $path = $pathToCopy;

            if($nextCave === 'end')
            {
                $path[] = 'end';
                $paths[] = $path;
            }
            else if(ctype_upper($nextCave))
            {
                $path[] = $nextCave;
                $this->visitPaths($nextCave, $paths, $path, $allowTwice);
            }
            else
            {
                if(!in_array($nextCave, $path))
                {
                    $path[] = $nextCave;
                    $this->visitPaths($nextCave, $paths, $path, $allowTwice);
                }
                else if($allowTwice)
                {
                    $path[] = $nextCave;
                    $this->visitPaths($nextCave, $paths, $path, false);
                }
            }
        }
    }
}
