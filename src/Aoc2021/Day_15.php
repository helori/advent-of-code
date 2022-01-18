<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use Graphp\Algorithms\ShortestPath\Dijkstra;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;

class Day_15 extends Aoc
{
    protected $matrix = null;

    protected function init()
    {
        $this->matrix = array_filter($this->lines);
        foreach($this->matrix as $i => $line){
            $this->matrix[$i] = $this->toInts(str_split(trim($line)));
        }
    }

    protected function runPart1()
    {
        return $this->findShortestDijkstra();
    }

    protected function runPart2()
    {
        $this->matrix = $this->replicateMatrix($this->matrix, 5);
        return $this->findShortestDijkstra();
    }

    protected function findShortestDijkstra()
    {
        $graph = new Graph();

        $rows = $this->matrixNumRows($this->matrix);
        $cols = $this->matrixNumCols($this->matrix);

        for($r=0; $r<$rows; $r++)
        {
            for($c=0; $c<$cols; $c++)
            {
                $i = $r * $cols + $c;
                $graph->createVertex($i);
            }   
        }

        for($r=0; $r<$rows; $r++)
        {
            for($c=0; $c<$cols; $c++)
            {
                $i = $r * $cols + $c;
                $vertex = $graph->getVertex($i);

                if($r>0){
                    $j = ($r-1) * $cols + $c;
                    $edge = $graph->getVertex($i)->createEdgeTo($graph->getVertex($j));
                    $edge->setWeight($this->matrix[$r-1][$c]);
                }
                if($r<$rows-1){
                    $j = ($r+1) * $cols + $c;
                    $edge = $graph->getVertex($i)->createEdgeTo($graph->getVertex($j));
                    $edge->setWeight($this->matrix[$r+1][$c]);
                }
                if($c>0){
                    $j = $r * $cols + ($c-1);
                    $edge = $graph->getVertex($i)->createEdgeTo($graph->getVertex($j));
                    $edge->setWeight($this->matrix[$r][$c-1]);
                }
                if($c<$cols-1){
                    $j = $r * $cols + ($c+1);
                    $edge = $graph->getVertex($i)->createEdgeTo($graph->getVertex($j));
                    $edge->setWeight($this->matrix[$r][$c+1]);
                }
            }
        }

        $alg = new Dijkstra($graph->getVertex(0));
        $distance = $alg->getDistance($graph->getVertex($rows * $cols - 1));
        
        return $distance;
    }

    // Solution "maison" qui marche, mais prend + de temps :
    protected function findShortest()
    {
        $rows = $this->matrixNumRows($this->matrix);
        $cols = $this->matrixNumCols($this->matrix);
        
        $paths = [
            '0_0' => [
                'r' => 0,
                'c' => 0,
                'sum' => 0,
                'from' => null,
            ],
        ];

        // Quand plus aucun des chemins exploré n'a de piste, on arrête
        $canExplore = true;
        while($canExplore)
        {
            $newPaths = $paths;
            $canExplore = false;

            foreach($paths as $path)
            {
                $r = $path['r'];
                $c = $path['c'];

                $canExplorePath = false;

                if(($r < ($rows - 1)) && ($path['from'] !== 'b'))
                {
                    $canExplorePath |= $this->tryPath('t', $r + 1, $c, $path, $newPaths);
                }
                if(($c < ($cols - 1)) && ($path['from'] !== 'r'))
                {
                    $canExplorePath |= $this->tryPath('l', $r, $c + 1, $path, $newPaths);
                }
                if(($r > 0) && ($path['from'] !== 't'))
                {
                    $canExplorePath |= $this->tryPath('b', $r - 1, $c, $path, $newPaths);
                }
                if(($c > 0) && ($path['from'] !== 'l'))
                {
                    $canExplorePath |= $this->tryPath('r', $r, $c - 1, $path, $newPaths);
                }

                $canExplore |= $canExplorePath;
            }
            $paths = $newPaths;
        }
        
        $lastKey = ($rows - 1).'_'.($cols - 1);
        return $paths[$lastKey]['sum'];
    }

    protected function tryPath($from, $r, $c, $path, &$newPaths)
    {
        $key = $r.'_'.$c;
        $sum = $path['sum'] + $this->matrix[$r][$c];
        
        if(!isset($newPaths[$key]) || $newPaths[$key]['sum'] > $sum){
            $newPaths[$key] = [
                'r' => $r,
                'c' => $c,
                'sum' => $sum,
                'from' => $from,
            ];
            return true;
        }
        return false;
    }

    

    protected function replicateMatrix($matrix, $dimensions)
    {
        $rows = $this->matrixNumRows($matrix);
        $cols = $this->matrixNumCols($matrix);

        // Replicate horizontally
        for($block=1; $block<$dimensions; $block++)
        {
            for($r=0; $r<$rows; $r++)
            {
                for($c=0; $c<$cols; $c++)
                {
                    $matrix[$r][$block * $cols + $c] = $this->incrementValue($matrix[$r][$c], $block);
                }
            }
        }

        // Replicate vertically
        for($block=1; $block<$dimensions; $block++)
        {
            for($r=0; $r<$rows; $r++)
            {
                $matrix[$block * $rows + $r] = $this->incrementVector($matrix[$r], $block);
            }
        }

        return $matrix;
    }

    protected function incrementVector($vector, $inc)
    {
        return array_map(function($value) use($inc) {
            return $this->incrementValue($value, $inc);
        }, $vector);
    }

    protected function incrementValue($value, $inc)
    {
        $newVal = $value + $inc;
        return ($newVal > 9) ? ($newVal - 9) : $newVal;
    }
}
