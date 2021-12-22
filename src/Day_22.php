<?php

namespace Aoc;

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
                'actions' => [$action],
                'x_min' => $xMin,
                'x_max' => $xMax,
                'y_min' => $yMin,
                'y_max' => $yMax,
                'z_min' => $zMin,
                'z_max' => $zMax,
            ];
            $instructions[] = $instruction;
        }
        $this->instructions = $instructions;
    }

    protected function runPart1()
    {
        $minX = -50;
        $maxX = 50;
        $minY = -50;
        $maxY = 50;
        $minZ = -50;
        $maxZ = 50;

        $instructions = $this->filterInstructions($minX, $maxX, $minY, $maxY, $minZ, $maxZ);
        return $this->applyInstructions($instructions);
    }

    protected function filterInstructions($minX, $maxX, $minY, $maxY, $minZ, $maxZ)
    {
        $filtered = [];
        foreach($this->instructions as $i)
        {
            if(($i['x_min'] >= $minX && $i['x_min'] <= $maxX) || ($i['x_max'] >= $minX && $i['x_max'] <= $maxX) &&
                ($i['y_min'] >= $minY && $i['y_min'] <= $maxY) || ($i['y_max'] >= $minY && $i['y_max'] <= $maxY) &&
                ($i['z_min'] >= $minZ && $i['z_min'] <= $maxZ) || ($i['z_max'] >= $minZ && $i['z_max'] <= $maxZ))
            {
                $i['x_min'] = max($minX, $i['x_min']);
                $i['x_max'] = min($maxX, $i['x_max']);

                $i['y_min'] = max($minY, $i['y_min']);
                $i['y_max'] = min($maxY, $i['y_max']);

                $i['z_min'] = max($minZ, $i['z_min']);
                $i['z_max'] = min($maxZ, $i['z_max']);

                $filtered[] = $i;
            }
        }
        return $filtered;
    }

    protected function applyInstructions($instructions)
    {
        $ons = [];
        foreach($instructions as $i)
        {
            $this->applyInstruction($i, $ons);
        }
        return count($ons);
    }

    protected function applyInstruction($i, array &$ons)
    {
        if($i['actions'][0] === 'off')
        {
            for($x=$i['x_min']; $x<=$i['x_max']; $x++)
            {
                for($y=$i['y_min']; $y<=$i['y_max']; $y++)
                {
                    for($z=$i['z_min']; $z<=$i['z_max']; $z++)
                    {
                        $key = $x.'_'.$y.'_'.$z;
                        if(isset($ons[$key])){
                            unset($ons[$key]);
                        }
                    }
                }
            }
        }

        if($i['actions'][0] === 'on')
        {
            for($x=$i['x_min']; $x<=$i['x_max']; $x++)
            {
                for($y=$i['y_min']; $y<=$i['y_max']; $y++)
                {
                    for($z=$i['z_min']; $z<=$i['z_max']; $z++)
                    {
                        $key = $x.'_'.$y.'_'.$z;
                        if(!isset($ons[$key])){
                            $ons[$key] = true;
                        }
                    }
                }
            }
        }
    }

    protected function runPart2()
    {
        return 0;
        $minX = null;
        $maxX = null;
        $minY = null;
        $maxY = null;
        $minZ = null;
        $maxZ = null;

        foreach($this->instructions as $i)
        {
            $minX = is_null($minX) ? $i['x_min'] : min($minX, $i['x_min']);
            $maxX = is_null($maxX) ? $i['x_max'] : max($maxX, $i['x_max']);

            $minY = is_null($minY) ? $i['y_min'] : min($minY, $i['y_min']);
            $maxY = is_null($maxY) ? $i['y_max'] : max($maxY, $i['y_max']);

            $minZ = is_null($minZ) ? $i['z_min'] : min($minZ, $i['z_min']);
            $maxZ = is_null($maxZ) ? $i['z_max'] : max($maxZ, $i['z_max']);
        }
        //dd($minX, $maxX, $minY, $maxY, $minZ, $maxZ);

        $stepSize = 100;
        $stepsX = intVal(ceil(($maxX - $minX) / $stepSize));
        $stepsY = intVal(ceil(($maxY - $minY) / $stepSize));
        $stepsZ = intVal(ceil(($maxZ - $minZ) / $stepSize));

        $count = 0;
        
        for($stepX=0; $stepX<$stepsX; $stepX++)
        {
            dump("Progress : ".(100 * $stepX / $stepsX)." %");

            $x1 = $minX + $stepX * $stepSize;
            $x2 = $minX + ($stepX + 1) * $stepSize;

            for($stepY=0; $stepY<$stepsY; $stepY++)
            {
                $y1 = $minY + $stepY * $stepSize;
                $y2 = $minY + ($stepY + 1) * $stepSize;

                for($stepZ=0; $stepZ<$stepsZ; $stepZ++)
                {
                    $z1 = $minZ + $stepZ * $stepSize;
                    $z2 = $minZ + ($stepZ + 1) * $stepSize;
                    
                    $instructions = $this->filterInstructions($x1, $x2, $y1, $y2, $z1, $z2);
                    $c = $this->applyInstructions($instructions);
                    $count += $c;

                    /*if($c > 0){
                        dump("Block $stepX $stepY $stepZ : $c");
                    }*/
                }   
            }
        }


        /*$size = 1000;
        $minX = -$size;
        $maxX = $size;
        $minY = -$size;
        $maxY = $size;
        $minZ = -$size;
        $maxZ = $size;

        $instructions = $this->filterInstructions($minX, $maxX, $minY, $maxY, $minZ, $maxZ);

        foreach($instructions as $i)
        {
            $this->applyInstruction($i);
        }*/

        return $count;
    }

    protected function combineInstructions()
    {
        $combined = [];

        foreach($this->instructions as $instruction)
        {
            foreach($combined as $c)
            {
                $overlapCube = $this->overlapCube($instruction, $c);
                $subCubes = $this->subCubes($instruction, $c);
            }
        }
    }
}
