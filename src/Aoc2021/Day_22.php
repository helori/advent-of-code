<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_22 extends Aoc
{
    protected $instructions = null;

    protected function init()
    {
        $this->lines = array_filter($this->lines);
        $instructions = [];
        foreach($this->lines as $line)
        {
            $parts = explode(' ', trim($line));
            $action = $parts[0];
            $coordinates = $parts[1];

            $xMin = intVal(Str::before(Str::after($coordinates, 'x='), '..'));
            $xMax = intVal(Str::before(Str::after($coordinates, '..'), ','));
            
            $coordinates = Str::after($coordinates, ',');
            $yMin = intVal(Str::before(Str::after($coordinates, 'y='), '..'));
            $yMax = intVal(Str::before(Str::after($coordinates, '..'), ','));

            $coordinates = Str::after($coordinates, ',');
            $zMin = intVal(Str::before(Str::after($coordinates, 'z='), '..'));
            $zMax = intVal(Str::before(Str::after($coordinates, '..'), ','));

            $instruction = [
                'action' => $action,
                'cube' => [$xMin, $xMax, $yMin, $yMax, $zMin, $zMax,]
            ];
            $instructions[] = $instruction;
        }
        $this->instructions = $instructions;
    }

    protected function runPart1()
    {
        $instructions = $this->filterInstructions([-50, 50, -50, 50, -50, 50]);
        $cuboids = $this->runInstructions($instructions);
        return $this->countCubes($cuboids);
    }

    protected function runPart2()
    {
        $cuboids = $this->runInstructions($this->instructions);
        return $this->countCubes($cuboids);
    }

    protected function filterInstructions($cuboidFilter)
    {
        $filtered = [];
        foreach($this->instructions as $i)
        {
            $cuboid = $this->overlapCube($i['cube'], $cuboidFilter);
            if(!is_null($cuboid))
            {
                $filtered[] = [
                    'cube' => $cuboid,
                    'action' => $i['action'],
                ];
            }
        }
        return $filtered;
    }

    protected function runInstructions($instructions)
    {
        $cubes = [];

        foreach($instructions as $instruction)
        {
            // Nouveaux cuboids allumés après cette instruction
            $newCubes = [];

            $currentCube = $instruction['cube'];

            // Si l'instruction est d'"allumer", alors on ajoute le nouveau cuboid.
            // Si l'instruction est d'"éteindre", alors on ne l'ajoute pas.
            // Dans les 2 cas, on retirera ses intersections avec les cuboids déjà allumés.
            if($instruction['action'] === 'on')
            {
                $newCubes[] = $currentCube;
            }

            foreach($cubes as $cube)
            {
                // Intersection des 2 cuboids
                $inter = $this->overlapCube($currentCube, $cube);

                // Pas d'intersection, le cuboid allumé est ajouté en entier
                if(is_null($inter))
                {
                    $newCubes[] = $cube;
                }
                // Intersection : on ajoute les parties du cuboid qui restent allumées
                else
                {
                    // à gauche
                    if($cube[0] < $inter[0]){
                        $newCubes[] = [$cube[0], $inter[0]-1, $cube[2], $cube[3], $cube[4], $cube[5]];
                    }
                    // à droite
                    if($cube[1] > $inter[1]){
                        $newCubes[] = [$inter[1]+1, $cube[1], $cube[2], $cube[3], $cube[4], $cube[5]];
                    }
                    // en bas
                    if($cube[2] < $inter[2]){
                        $newCubes[] = [$inter[0], $inter[1], $cube[2], $inter[2]-1, $cube[4], $cube[5]];
                    }
                    // en haut
                    if($cube[3] > $inter[3]){
                        $newCubes[] = [$inter[0], $inter[1], $inter[3]+1, $cube[3], $cube[4], $cube[5]];
                    }
                    // derrière
                    if($cube[4] < $inter[4]){
                        $newCubes[] = [$inter[0], $inter[1], $inter[2], $inter[3], $cube[4], $inter[4]-1];
                    }
                    // devant
                    if($cube[5] > $inter[5]){
                        $newCubes[] = [$inter[0], $inter[1], $inter[2], $inter[3], $inter[5]+1, $cube[5]];
                    }
                }
            }
            // Les nouveaux cubes allumés sont stockés pour la prochaine instruction
            $cubes = $newCubes;
        }
        return $cubes;
    }

    protected function countCubes($cuboids)
    {
        $count = 0;
        foreach($cuboids as $c)
        {
            $count += ($c[1] - $c[0] + 1) * ($c[3] - $c[2] + 1) * ($c[5] - $c[4] + 1);
        }
        return $count;
    }

    protected function overlapCube($c1, $c2)
    {
        $x1 = max($c1[0], $c2[0]);
        $x2 = min($c1[1], $c2[1]);

        $y1 = max($c1[2], $c2[2]);
        $y2 = min($c1[3], $c2[3]);

        $z1 = max($c1[4], $c2[4]);
        $z2 = min($c1[5], $c2[5]);

        if($x1 <= $x2 && $y1 <= $y2 && $z1 <= $z2)
        {
            return [$x1, $x2, $y1, $y2, $z1, $z2];
        }
        return null;
    }
}
