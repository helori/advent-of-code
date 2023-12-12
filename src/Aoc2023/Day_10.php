<?php

namespace Aoc\Aoc2023;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_10 extends Aoc
{
    protected $matrix;
    protected $rows;
    protected $cols;
    protected $path;
    
    protected function init()
    {
        $this->matrix = [];
        foreach($this->lines as $line)
        {
            $this->matrix[] = str_split($line);
        }
        $this->rows = $this->matrixNumRows($this->matrix);
        $this->cols = $this->matrixNumCols($this->matrix);
        $this->path = $this->findPath();
    }
    
    protected function runPart1()
    {
        $middle = count($this->path) / 2;
        return $middle;
    }

    protected function runPart2()
    {
        $inside = false;
        $crossing = null;
        $insideCount = 0;

        $this->readMatrix($this->matrix, function($v, $r, $c) use(&$inside, &$crossing, &$insideCount)
        {
            $v = $this->isPathValue($r, $c);
            if($v !== null)
            {
                if($v === '|'){
                    $inside = !$inside;
                }
                else if($v === 'F' || $v === 'L'){
                    $crossing = $v;
                }
                else if($v === 'J'){
                    if($crossing === 'F') $inside = !$inside;
                }
                else if($v === 'J'){
                    if($crossing === 'F'){
                        $inside = !$inside;
                    }
                    $crossing = null;
                }
                else if($v === '7'){
                    if($crossing === 'L'){
                        $inside = !$inside;
                    }
                    $crossing = null;
                }
            }
            else
            {
                $insideCount += $inside ? 1 : 0;
            }
        });

        return $insideCount;
    }

    protected function isPathValue($r, $c)
    {
        foreach($this->path as $tile){
            if($tile[0] === $c && $tile[1] === $r){
                return $tile[2];
            }
        }
        return null;
    }

    protected function findPath()
    {
        $start = $this->findStartPosition();
        $opts = $this->optionsFrom($start[0], $start[1], null, null);
        $sValue = $this->findSValue($start, $opts[0], $opts[1]);
        $start[2] = $sValue;

        $paths = [];
        foreach($opts as $opt)
        {
            $paths[] = [ $start, $opt ];
        }

        // On choisit une des 2 directions
        $path = $paths[0];
        
        // Dernière valeur trouvée pour le chemin
        $value = $path[count($path) - 1][2];
        while($value !== 'S') // La boucle est bouclée
        {
            $lastTile = $path[count($path) - 1];
            $prevTile = count($path) > 1 ? $path[count($path) - 2] : null;
            $tile = $this->next($lastTile[0], $lastTile[1], $lastTile[2], $prevTile ? $prevTile[0] : null, $prevTile ? $prevTile[1] : null);
            $value = $tile[2];
            if($value !== 'S'){
                $path[] = $tile;
            }
        }
        return $path;
    }

    protected function findStartPosition()
    {
        $pos = null;
        $this->readMatrix($this->matrix, function($v, $r, $c) use(&$pos)
        {
            if($v === 'S'){
                $pos = [$c, $r, 'S'];
                return true;
            }
        });
        return $pos;
    }

    protected function findSValue($start, $opt1, $opt2)
    {
        $sValue = null;
        if($opt1[0] === $start[0]-1)
        {
            if($opt2[0] === $start[0]){
                if($opt2[1] === $start[1]-1) $sValue = 'J';
                else if($opt2[1] === $start[1]+1) $sValue = '7';
            }
            else if($opt2[0] === $start[1]+1) $sValue = '-';
        }
        else if($opt1[0] === $start[0]+1)
        {
            if($opt2[0] === $start[0]){
                if($opt2[1] === $start[1]-1) $sValue = 'L';
                else if($opt2[1] === $start[1]+1) $sValue = 'F';
            }
            else if($opt2[0] === $start[1]+1) $sValue = '-';
        }
        else if($opt1[0] === $start[0])
        {
            if($opt1[1] === $start[1]-1)
            {
                if($opt2[0] === $start[0]-1) $sValue = 'J';
                else if($opt2[0] === $start[0]) $sValue = '|';
                else if($opt2[0] === $start[0]+1) $sValue = 'L';
            }
            else if($opt1[1] === $start[1]+1)
            {
                if($opt2[0] === $start[0]-1) $sValue = '7';
                else if($opt2[0] === $start[0]) $sValue = '|';
                else if($opt2[0] === $start[0]+1) $sValue = 'F';
            }
        }
        return $sValue;
    }

    protected function renderPaths($paths)
    {
        foreach($paths as $path)
        {
            $this->renderPath($path);
        }
    }

    protected function renderPath($path)
    {
        $path = array_map(function($p){
            return $p[2];
        }, $path);
        echo implode('',  $path)."\n";
    }

    protected function next($x, $y, $v, $ex, $ey)
    {
        if($v === '-'){
            $x = ($x+1 === $ex) ? $x-1 : $x+1;
        }else if($v === '|'){
            $y = ($y+1 === $ey) ? $y-1 : $y+1;
        }else if($v === 'F'){
            $x = ($x === $ex) ? $x+1 : $x;
            $y = ($y === $ey) ? $y+1 : $y;
        }else if($v === '7'){
            $x = ($x === $ex) ? $x-1 : $x;
            $y = ($y === $ey) ? $y+1 : $y;
        }else if($v === 'J'){
            $x = ($x === $ex) ? $x-1 : $x;
            $y = ($y === $ey) ? $y-1 : $y;
        }else if($v === 'L'){
            $x = ($x === $ex) ? $x+1 : $x;
            $y = ($y === $ey) ? $y-1 : $y;
        }

        return [
            $x, $y, $this->matrix[$y][$x],
        ];
    }
    
    protected function optionsFrom($x, $y, $ex, $ey)
    {
        $options = [];

        // Left
        $nx = $x - 1;
        $ny = $y;
        if($nx >= 0 && ($nx !== $ex && $y !== $ey))
        {
            $v = $this->matrix[$ny][$nx];
            if(in_array($v, ['-', 'L', 'F'])){
                $options[] = [$nx, $ny, $v];
            }
        }

        // Right
        $nx = $x + 1;
        $ny = $y;
        if($nx < $this->cols && ($nx !== $ex && $y !== $ey))
        {
            $v = $this->matrix[$ny][$nx];
            if(in_array($v, ['-', '7', 'J'])){
                $options[] = [$nx, $ny, $v];
            }
        }
        
        // Top
        $nx = $x;
        $ny = $y - 1;
        if($ny >= 0 && ($nx !== $ex && $y !== $ey))
        {
            $v = $this->matrix[$ny][$nx];
            if(in_array($v, ['|', '7', 'F'])){
                $options[] = [$nx, $ny, $v];
            }
        }

        // Bottom
        $nx = $x;
        $ny = $y + 1;
        if($ny < $this->rows && ($nx !== $ex && $y !== $ey))
        {
            $v = $this->matrix[$ny][$nx];
            if(in_array($v, ['|', 'L', 'J'])){
                $options[] = [$nx, $ny, $v];
            }
        }

        return $options;
    }
}
