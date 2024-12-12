<?php

namespace Aoc\Aoc2024;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_12 extends Aoc
{
    protected $map;
    protected $regions;

    protected function init()
    {
        $this->findRegions();
    }

    protected function runPart1()
    {
        $price = 0;

        foreach($this->regions as $region)
        {
            $area = count($region);
            $perimeter = 0;

            foreach($region as $plant)
            {
                $r = $plant[0];
                $c = $plant[1];
                
                if(!array_key_exists(($r-1).'_'.$c, $region)){
                    $perimeter += 1;
                }
                if(!array_key_exists(($r+1).'_'.$c, $region)){
                    $perimeter += 1;
                }
                if(!array_key_exists($r.'_'.($c+1), $region)){
                    $perimeter += 1;
                }
                if(!array_key_exists($r.'_'.($c-1), $region)){
                    $perimeter += 1;
                }
            }
            //dump('p = '.$perimeter.' | area = '.$area);
            $price += $area * $perimeter;
        }

        return $price;
    }

    protected function runPart2()
    {
        $price = 0;

        foreach($this->regions as $region)
        {
            $area = count($region);
            $edgesLeft = [];
            $edgesRight = [];
            $edgesTop = [];
            $edgesBottom = [];

            foreach($region as $key => $plant)
            {
                $r = $plant[0];
                $c = $plant[1];

                if(!array_key_exists(($r-1).'_'.$c, $region)){
                    $edgesTop[$key] = $plant;
                }
                if(!array_key_exists(($r+1).'_'.$c, $region)){
                    $edgesBottom[$key] = $plant;
                }
                if(!array_key_exists($r.'_'.($c+1), $region)){
                    $edgesRight[$key] = $plant;
                }
                if(!array_key_exists($r.'_'.($c-1), $region)){
                    $edgesLeft[$key] = $plant;
                }
            }

            //dd(array_keys($region), array_keys($edgesTop), array_keys($edgesBottom), array_keys($edgesRight), array_keys($edgesLeft));

            $sides = 0;

            // Top edge
            $groups = [];
            foreach($edgesTop as $edge)
            {
                $r = $edge[0];
                $c = $edge[1];
                $key = 't_'.$r;
                if(!array_key_exists($key, $groups)){
                    $groups[$key] = [];
                }
                $groups[$key][] = $c;
            }
            foreach($edgesBottom as $edge)
            {
                $r = $edge[0];
                $c = $edge[1];
                $key = 'b_'.$r;
                if(!array_key_exists($key, $groups)){
                    $groups[$key] = [];
                }
                $groups[$key][] = $c;
            }
            foreach($edgesLeft as $edge)
            {
                $r = $edge[0];
                $c = $edge[1];
                $key = 'l_'.$c;
                if(!array_key_exists($key, $groups)){
                    $groups[$key] = [];
                }
                $groups[$key][] = $r;
            }
            foreach($edgesRight as $edge)
            {
                $r = $edge[0];
                $c = $edge[1];
                $key = 'r_'.$c;
                if(!array_key_exists($key, $groups)){
                    $groups[$key] = [];
                }
                $groups[$key][] = $r;
            }
            
            $sides = 0;
            foreach($groups as &$group)
            {
                $sides += $this->countConsecutives($group);
            }
            //dd($sides);
            // ---
            
            //dump($groups);
            //dump('sides = '.$sides.' | area = '.$area);
            $price += $area * $sides;
        }

        return $price;
    }

    protected function countConsecutives(&$indexes)
    {
        asort($indexes);
        $indexes = array_values($indexes);
        $count = 1;
        for($i=1; $i<count($indexes); ++$i){
            if($indexes[$i] > $indexes[$i-1]+1){
                $count++;
            }
        }
        return $count;
    }

    protected function findRegions()
    {
        $this->regions = [];
        $map = $this->fileMatrix(false, '');

        $this->readMatrix($map, function($v, $r, $c, $numRows, $numCols) use(&$regions, &$map)
        {
            // Check if this plant already belongs to a region :
            $belongsToRegion = false;
            foreach($this->regions as $region)
            {
                if(array_key_exists($r.'_'.$c, $region)){
                    $belongsToRegion = true;
                    break;
                }
            }
            if(!$belongsToRegion)
            {
                // Build new region : 
                $region = [
                    $r.'_'.$c => [$r, $c],
                ];
                $this->findNeighbours($v, $r, $c, $numRows, $numCols, $map, $region);

                $this->regions[] = $region;
            }
        });
    }

    protected function findNeighbours($v, $r, $c, $numRows, $numCols, &$map, &$region)
    {
        if(($r > 0) && !array_key_exists(($r-1).'_'.$c, $region) && ($map[$r-1][$c] === $v)){
            $region[($r-1).'_'.$c] = [$r-1, $c];
            $this->findNeighbours($v, $r-1, $c, $numRows, $numCols, $map, $region);
        }
        if(($c > 0) && !array_key_exists($r.'_'.($c-1), $region) && ($map[$r][$c-1] === $v)){
            $region[($r).'_'.($c-1)] = [$r, $c-1];
            $this->findNeighbours($v, $r, $c-1, $numRows, $numCols, $map, $region);
        }
        if(($r < $numRows-1) && !array_key_exists(($r+1).'_'.$c, $region) && ($map[$r+1][$c] === $v)){
            $region[($r+1).'_'.($c)] = [$r+1, $c];
            $this->findNeighbours($v, $r+1, $c, $numRows, $numCols, $map, $region);
        }
        if(($c < $numCols-1) && !array_key_exists($r.'_'.($c+1), $region) && ($map[$r][$c+1] === $v)){
            $region[($r).'_'.($c+1)] = [$r, $c+1];
            $this->findNeighbours($v, $r, $c+1, $numRows, $numCols, $map, $region);
        }
    }
}
