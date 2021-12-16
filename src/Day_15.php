<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_15 extends Aoc
{
    protected $matrix = null;

    protected function init()
    {
        $this->matrix = array_filter($this->lines);
        foreach($this->matrix as $i => $line){
            $this->matrix[$i] = $this->toInts(str_split(trim($line)));
        }
        //$this->dump($this->matrix);
    }

    protected function runPart1()
    {
        return $this->findShortest($this->matrix);
    }

    protected function runPart2()
    {
        $matrix = $this->replicateMatrix($this->matrix, 5);
        //$this->renderMatrix($matrix);
        return $this->findShortest($matrix);
    }

    protected function findShortest($matrix)
    {
        $rows = $this->matrixNumRows($matrix);
        $cols = $this->matrixNumCols($matrix);
        
        $paths = [
            '0_0' => [
                'r' => 0,
                'c' => 0,
                'sum' => 0,
                'from' => null,
                'key' => '0_0',
            ],
        ];

        // Pas optimal mais permet de trouver le bon résultat.
        // Si on n'avance que vers le bas et la droite, alors $steps = $rows + $cols - 2
        // Ça fonctionne bien ainsi pour la partie 1.
        //
        // La partie 2 nécessite de contourner des blocs par la gauche ou par le haut.
        // Dans l'exemple donné, il n'y a pas de contournements, et donc l'algo de la partie 1 fonctionne.
        // Mais avec le puzzle input, il y a des contournements !
        // Le nombre de step nécessaires augmente donc pour arriver en bas à droite
        // En doublant le nombre de steps, on trouve le résultat, mais on peut peut-être en faire moins ?
        $steps = 2 * ($rows + $cols - 2);
        $step = 0;

        while($step < $steps)
        {
            $newPaths = $paths;
            foreach($paths as $path)
            {
                $r = $path['r'];
                $c = $path['c'];

                if(($r < ($rows - 1)) && ($path['from'] !== 'b'))
                {
                    $key = ($r + 1).'_'.$c;
                    $sum = $path['sum'] + $matrix[$r + 1][$c];
                    if(!isset($newPaths[$key]) || $newPaths[$key]['sum'] > $sum){
                        $newPaths[$key] = [
                            'r' => $r + 1,
                            'c' => $c,
                            'sum' => $sum,
                            'from' => 't',
                            'key' => $key,
                        ];
                    }
                }
                if(($c < ($cols - 1)) && ($path['from'] !== 'r'))
                {
                    $key = $r.'_'.($c + 1);
                    $sum = $path['sum'] + $matrix[$r][$c + 1];
                    if(!isset($newPaths[$key]) || $newPaths[$key]['sum'] > $sum){
                        $newPaths[$key] = [
                            'r' => $r,
                            'c' => $c + 1,
                            'sum' => $sum,
                            'from' => 'l',
                            'key' => $key,
                        ];
                    }
                }
                if(($r > 0) && ($path['from'] !== 't'))
                {
                    $key = ($r - 1).'_'.$c;
                    $sum = $path['sum'] + $matrix[$r - 1][$c];
                    if(!isset($newPaths[$key]) || $newPaths[$key]['sum'] > $sum){
                        $newPaths[$key] = [
                            'r' => $r - 1,
                            'c' => $c,
                            'sum' => $sum,
                            'from' => 'b',
                            'key' => $key,
                        ];
                    }
                }
                if(($c > 0) && ($path['from'] !== 'l'))
                {
                    $key = $r.'_'.($c - 1);
                    $sum = $path['sum'] + $matrix[$r][$c - 1];
                    if(!isset($newPaths[$key]) || $newPaths[$key]['sum'] > $sum){
                        $newPaths[$key] = [
                            'r' => $r,
                            'c' => $c - 1,
                            'sum' => $sum,
                            'from' => 'r',
                            'key' => $key,
                        ];
                    }
                }
            }
            $paths = $newPaths;
            $step++;
        }
        
        $lastKey = ($rows - 1).'_'.($cols - 1);
        //$this->dump($paths[$lastKey]);

        return $paths[$lastKey]['sum'];
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
