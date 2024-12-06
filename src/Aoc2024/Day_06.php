<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_06 extends Aoc
{
    protected $matrix;
    protected $pos;

    protected function init()
    {
        $this->matrix = $this->fileMatrix(false, '');
        //$this->renderMatrix($this->matrix);
        $this->pos = null;

        foreach($this->matrix as $r => $row)
        {
            foreach($row as $c => $val){
                if($val === '^'){
                    $this->pos = [$r, $c];
                    break;
                }
            }
            if(!is_null($this->pos)){
                break;
            }
        }
    }

    protected function runPart1()
    {
        list($visited, $obstacles, $loopig) = $this->parseMap($this->matrix);
        return count($visited);
    }

    protected function runPart2()
    {
        $numCols = $this->matrixNumCols($this->matrix);
        $numRows = $this->matrixNumRows($this->matrix);
        $loops = 0;

        for($r=0;$r<$numRows; ++$r)
        {
            for($c=0;$c<$numCols; ++$c)
            {
                $matrix = $this->matrix;
                $matrix[$r][$c] = '#';
                list($visited, $obstacles, $loopig) = $this->parseMap($matrix);
                $loops += $loopig ? 1 : 0;
            }
        }

        return $loops;
    }

    protected function parseMap($matrix)
    {
        $numCols = $this->matrixNumCols($matrix);
        $numRows = $this->matrixNumRows($matrix);
        $p = $this->pos;
        $dir = 'up';
        $out = false;
        $visited = [$p[0].'-'.$p[1] => true];
        $obstacles = [];
        $looping = false;

        while(!$out)
        {
            //dump($dir);
            if($dir === 'up')
            {
                $c = $p[1];
                for($r=$p[0]-1; $r>=0; $r--)
                {
                    if($matrix[$r][$c] === '#'){
                        $matrix[$r+1][$c] = '>';
                        $matrix[$p[0]][$p[1]] = '.';
                        $dir = 'right';
                        $p = [$r+1, $c];

                        $obstacleKey = $r.'-'.$c.'-'.$dir;
                        if(array_key_exists($obstacleKey, $obstacles)){
                            $looping = true;
                        }
                        $obstacles[$obstacleKey] = true;
                        //$this->renderMatrix($matrix);
                        break;
                    }else{
                        $visited[$r.'-'.$c] = true;
                        if($r === 0){
                            $out = true;
                            break;
                        }
                    }
                }
            }
            else if($dir === 'right')
            {
                $r = $p[0];
                for($c=$p[1]+1; $c<$numCols; $c++)
                {
                    if($matrix[$r][$c] === '#'){
                        $matrix[$r][$c-1] = 'v';
                        $matrix[$p[0]][$p[1]] = '.';
                        $dir = 'down';
                        $p = [$r, $c-1];

                        $obstacleKey = $r.'-'.$c.'-'.$dir;
                        if(array_key_exists($obstacleKey, $obstacles)){
                            $looping = true;
                        }
                        $obstacles[$obstacleKey] = true;
                        //$this->renderMatrix($matrix);
                        break;
                    }else{
                        $visited[$r.'-'.$c] = true;
                        if($c === $numCols-1){
                            $out = true;
                            break;
                        }
                    }
                }
            }
            else if($dir === 'down')
            {
                $c = $p[1];
                for($r=$p[0]+1; $r<$numRows; $r++)
                {
                    if($matrix[$r][$c] === '#'){
                        $matrix[$r-1][$c] = '<';
                        $matrix[$p[0]][$p[1]] = '.';
                        $dir = 'left';
                        $p = [$r-1, $c];

                        $obstacleKey = $r.'-'.$c.'-'.$dir;
                        if(array_key_exists($obstacleKey, $obstacles)){
                            $looping = true;
                        }
                        $obstacles[$obstacleKey] = true;
                        //$this->renderMatrix($matrix);
                        break;
                    }else{
                        $visited[$r.'-'.$c] = true;
                        if($r === $numRows-1){
                            $out = true;
                            break;
                        }
                    }
                }
            }
            else if($dir === 'left')
            {
                $r = $p[0];
                for($c=$p[1]-1; $c<$numCols; $c--)
                {
                    if($matrix[$r][$c] === '#'){
                        $matrix[$r][$c+1] = '^';
                        $matrix[$p[0]][$p[1]] = '.';
                        $dir = 'up';
                        $p = [$r, $c+1];

                        $obstacleKey = $r.'-'.$c.'-'.$dir;
                        if(array_key_exists($obstacleKey, $obstacles)){
                            $looping = true;
                        }
                        $obstacles[$obstacleKey] = true;
                        //$this->renderMatrix($matrix);
                        break;
                    }else{
                        $visited[$r.'-'.$c] = true;
                        if($c === 0){
                            $out = true;
                            break;
                        }
                    }
                }
            }
            if($looping){
                break;
            }
        }

        return [
            $visited,
            $obstacles,
            $looping,
        ];
    }
}
