<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_20 extends Aoc
{
    protected $algo = null;
    protected $image = null;

    protected function init()
    {
        $this->algo = trim($this->lines[0]);
        $this->image = [];
        foreach($this->lines as $i => $line)
        {
            if($i >= 2 && $line){
                $this->image[] = str_split($line);
            }
        }
    }

    protected function runPart1()
    {
        $imageData = [
            'image' => $this->image,
            'outside_symbol' => '.',
        ];

        $imageData = $this->enhance($imageData, 2);
        return $this->countLitPixels($imageData['image']);
    }

    protected function runPart2()
    {
        $imageData = [
            'image' => $this->image,
            'outside_symbol' => '.',
        ];

        $imageData = $this->enhance($imageData, 50);
        return $this->countLitPixels($imageData['image']);
    }

    protected function countLitPixels($image)
    {
        $count = 0;
        $this->readMatrix($image, function($v) use(&$count) {
            if($v === '#'){
                $count++;
            }
        });
        return $count;
    }

    protected function enhance($imageData, $count)
    {
        for($i=0; $i<$count; ++$i)
        {
            $imageData = $this->processImage($imageData);
        }
        return $imageData;
    }

    protected function processImage($imageData)
    {
        $image = $imageData['image'];
        $outsideSymbol = $imageData['outside_symbol'];

        $newImage = [];
        
        $rows = $this->matrixNumRows($image);
        $cols = $this->matrixNumCols($image);

        for($r=-2; $r<$rows; ++$r)
        {
            $newRow = [];

            for($c=-2; $c<$cols; ++$c)
            {
                $symbols = '';
                for($rSub=$r; $rSub<$r+3; ++$rSub)
                {
                    for($cSub=$c; $cSub<$c+3; ++$cSub)
                    {
                        if($rSub < 0 || $cSub < 0 || $rSub >= $rows || $cSub >= $cols)
                        {
                            $symbols .= $outsideSymbol;
                        }
                        else{
                            $symbols .= $image[$rSub][$cSub];
                        }
                    }
                }
                $newRow[] = $this->convertSymbols($symbols);
            }
            $newImage[] = $newRow;
        }

        $symbols = implode('', array_fill(0, 9, $outsideSymbol));
        $newOutsideSymbol = $this->convertSymbols($symbols);

        return [
            'image' => $newImage,
            'outside_symbol' => $newOutsideSymbol,
        ];
    }

    protected function convertSymbols($symbols)
    {
        $symbols = str_replace('.', '0', $symbols);
        $symbols = str_replace('#', '1', $symbols);
        $position = intVal(base_convert($symbols, 2, 10));
        return $this->algo[$position];
    }
}
