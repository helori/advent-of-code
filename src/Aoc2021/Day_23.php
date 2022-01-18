<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_23 extends Aoc
{
    protected $tubeSize = null;
    protected $initialData = null;
    protected $minScore = null;

    // This is just to exit premaurely on tests
    protected $numExplores = 0;
    protected $maxExplores = 10;

    protected function init()
    {
        $this->lines = array_filter($this->lines);
        $this->tubeSize = count($this->lines)-3;

        $data = array_fill(0, 4 * $this->tubeSize + 8, null);
        $data[count($data) - 1] = 0;

        for($i=$this->tubeSize-1; $i>=0; $i--)
        {
            $letters = explode('#', trim($this->lines[$i + 2], ' #'));
            foreach($letters as $idx => $letter)
            {
                $data[$idx * $this->tubeSize + $this->tubeSize-1-$i] = $letter;
            }
        }
        $this->initialData = $data;
    }

    protected function runPart1()
    {
        return $this->findMinScore($this->initialData);
    }

    protected function runPart2()
    {
        array_splice($this->initialData, 1, 0, ['D', 'D']);
        array_splice($this->initialData, 5, 0, ['B', 'C']);
        array_splice($this->initialData, 9, 0, ['A', 'B']);
        array_splice($this->initialData, 13, 0, ['C', 'A']);
        
        return $this->findMinScore($this->initialData);
    }

    protected function findMinScore($data)
    {
        $this->tubeSize = (count($data) - 8) / 4;
        $this->minScore = null;
        $this->explore($data, 0);
        return $this->minScore;
    }

    protected function explore($data, $depth)
    {
        /*if($this->checkExit()){
            return;
        }*/

        //dump('#########');
        $message = "Depth : ".$depth.' | score : '.$data[count($data) - 1];
        if($this->minScore){
            $message .= ' | min : '.$this->minScore;
        }
        //dump($message);
        //$this->render2($data);

        // 28 first levels to follow progress in terminal :
        /*static $firstLevelCounter = 0;
        if($depth === 1){
            $firstLevelCounter++;
            dump("=> First Level : $firstLevelCounter");
        }*/

        if($this->checkWin($data))
        {
            $score = $data[count($data) - 1];
            $this->minScore = is_null($this->minScore) ? $score : min($score, $this->minScore);
            //dump("Win Score : $score");
        }
        else
        {
            for($p = 4 * $this->tubeSize; $p < 4 * $this->tubeSize + 7; $p++){
                for($t=0; $t<=3; $t++){
                    $this->fill($data, $p, $t, $depth + 1);
                }
            }

            for($p = 4 * $this->tubeSize; $p < 4 * $this->tubeSize + 7; $p++){
                for($t=0; $t<=3; $t++){
                    $this->park($data, $p, $t, $depth + 1);
                }
            }
        }

        //dump('All tried');
        //$this->render($data);
    }

    protected function fill($data, $p, $tubeIdx, $depth)
    {
        $t0 = $tubeIdx * $this->tubeSize;
        $tn = $t0 + $this->tubeSize - 1;

        $tubeLetter = $this->tubeLetter($tubeIdx);
        $letter = $data[$p];

        if($letter !== $tubeLetter){
            return;
        }

        if($this->free($data, $p, $tubeIdx) && !is_null($data[$p]) && is_null($data[$tn]))
        {
            for($t = $t0; $t<=$tn; $t++){
                if(!is_null($data[$t]) && $data[$t] !== $letter){
                    break;
                }
                if(is_null($data[$t])){
                    $data[$t] = $letter;
                    $data[$p] = null;

                    $stepScore = $this->letterFactor($letter) * ($this->distance($p, $tubeIdx) + ($tn - $t) + 1);
                    $prevScore = $data[count($data) - 1];
                    $score = $prevScore + $stepScore;
                    $data[count($data) - 1] = $score;

                    if(is_null($this->minScore) || $score < $this->minScore){
                        $this->explore($data, $depth);
                    }
                    break;
                }
            }
        }
    }

    protected function park($data, $p, $tubeIdx, $depth)
    {
        $t0 = $tubeIdx * $this->tubeSize;
        $tn = $t0 + $this->tubeSize - 1;

        $tubeLetter = $this->tubeLetter($tubeIdx);

        $ended = true;
        for($t = $tn; $t>=$t0; $t--){
            $ended &= ($data[$t] === $tubeLetter);
        }
        if($ended){
            return;
        }

        $containsAnotherLetter = false;
        for($t = $tn; $t>=$t0; $t--){
            if(!is_null($data[$t]) && ($data[$t] !== $tubeLetter)){
                $containsAnotherLetter = true;
                break;
            }
        }
        if(!$containsAnotherLetter){
            return;
        }

        if($this->free($data, $p, $tubeIdx) && is_null($data[$p]) && !is_null($data[$t0]))
        {
            for($t = $tn; $t>=$t0; $t--){
                if(!is_null($data[$t])){
                    $letter = $data[$t];
                    $data[$p] = $letter;
                    $data[$t] = null;

                    $stepScore = $this->letterFactor($letter) * ($this->distance($p, $tubeIdx) + ($tn - $t) + 1);
                    $prevScore = $data[count($data) - 1];
                    $score = $prevScore + $stepScore;
                    $data[count($data) - 1] = $score;

                    if(is_null($this->minScore) || $score < $this->minScore){
                        $this->explore($data, $depth);
                    }
                    break;
                }
            }
        }
    }

    protected function tubeLetter($tubeIdx)
    {
        $tubeLetter = 'A';
        for($i=0; $i<$tubeIdx; $i++){
            $tubeLetter++;
        }
        return $tubeLetter;
    }

    protected function checkWin($data)
    {
        $win = true;
        $letter = 'A';
        for($tubeIdx = 0; $tubeIdx<=3; $tubeIdx++)
        {
            for($t = $tubeIdx * $this->tubeSize; $t < ($tubeIdx+1) * $this->tubeSize; $t++)
            {
                $win &= ($data[$t] === $letter);
            }
            $letter++;
        }
        for($p = 4 * $this->tubeSize; $p < 4 * $this->tubeSize + 7; $p++)
        {
            $win &= is_null($data[$p]);
        }

        return $win;
    }

    protected function free($data, $p, $tubeIdx)
    {
        $offset = 4 * $this->tubeSize;
        $p -= $offset;

        $p0 = is_null($data[$offset + 0]);
        $p1 = is_null($data[$offset + 1]);
        $p2 = is_null($data[$offset + 2]);
        $p3 = is_null($data[$offset + 3]);
        $p4 = is_null($data[$offset + 4]);
        $p5 = is_null($data[$offset + 5]);
        $p6 = is_null($data[$offset + 6]);

        if($tubeIdx === 0)
        {
            if($p === 0) { return $p1; }
            else if($p === 1) { return true; } 
            else if($p === 2) { return true; }
            else if($p === 3) { return $p2; }
            else if($p === 4) { return $p2 && $p3; }
            else if($p === 5) { return $p2 && $p3 && $p4; }
            else if($p === 6) { return $p2 && $p3 && $p4 && $p5; }
        }
        else if($tubeIdx === 1)
        {
            if($p === 0) { return $p1 && $p2; }
            else if($p === 1) { return $p2; } 
            else if($p === 2) { return true; }
            else if($p === 3) { return true; }
            else if($p === 4) { return $p3; }
            else if($p === 5) { return $p3 && $p4; }
            else if($p === 6) { return $p3 && $p4 && $p5; }
        }
        else if($tubeIdx === 2)
        {
            if($p === 0) { return $p1 && $p2 && $p3; }
            else if($p === 1) { return $p2 && $p3; } 
            else if($p === 2) { return $p3; }
            else if($p === 3) { return true; }
            else if($p === 4) { return true; }
            else if($p === 5) { return $p4; }
            else if($p === 6) { return $p4 && $p5; }
        }
        else if($tubeIdx === 3)
        {
            if($p === 0) { return $p1 && $p2 && $p3 && $p4; }
            else if($p === 1) { return $p2 && $p3 && $p4; } 
            else if($p === 2) { return $p3 && $p4; }
            else if($p === 3) { return $p4; }
            else if($p === 4) { return true; }
            else if($p === 5) { return true; }
            else if($p === 6) { return $p5; }
        }
    }

    protected function distance($p, $tubeIdx)
    {
        $p -= 4 * $this->tubeSize;

        if($tubeIdx === 0)
        {
            if($p === 0) { return 2; }
            if($p === 1) { return 1; }
            if($p === 2) { return 1; }
            if($p === 3) { return 3; }
            if($p === 4) { return 5; }
            if($p === 5) { return 7; }
            if($p === 6) { return 8; }
        }
        else if($tubeIdx === 1)
        {
            if($p === 0) { return 4; }
            if($p === 1) { return 3; }
            if($p === 2) { return 1; }
            if($p === 3) { return 1; }
            if($p === 4) { return 3; }
            if($p === 5) { return 5; }
            if($p === 6) { return 6; }
        }
        else if($tubeIdx === 2)
        {
            if($p === 0) { return 6; }
            if($p === 1) { return 5; }
            if($p === 2) { return 3; }
            if($p === 3) { return 1; }
            if($p === 4) { return 1; }
            if($p === 5) { return 3; }
            if($p === 6) { return 4; }
        }
        else if($tubeIdx === 3)
        {
            if($p === 0) { return 8; }
            if($p === 1) { return 7; }
            if($p === 2) { return 5; }
            if($p === 3) { return 3; }
            if($p === 4) { return 1; }
            if($p === 5) { return 1; }
            if($p === 6) { return 2; }
        }
    }

    protected function render($data)
    {
        $text = '';
        for($tubeIdx = 0; $tubeIdx<=3; $tubeIdx++)
        {
            for($t = $tubeIdx * $this->tubeSize; $t < ($tubeIdx+1) * $this->tubeSize; $t++)
            {
                $text .= $data[$t] ?? '-';
            }
            $text .= ' | ';
        }
        for($p = 4 * $this->tubeSize; $p < 4 * $this->tubeSize + 7; $p++)
        {
            $text .= $data[$p] ?? '-';
        }

        dump($text);
    }

    protected function render2($data)
    {
        //echo "\n";
        //dump("Score : ".$data[count($data) - 1]);
        echo $data[4 * $this->tubeSize + 0] ?? '-';
        echo $data[4 * $this->tubeSize + 1] ?? '-';
        echo '-';
        echo $data[4 * $this->tubeSize + 2] ?? '-';
        echo '-';
        echo $data[4 * $this->tubeSize + 3] ?? '-';
        echo '-';
        echo $data[4 * $this->tubeSize + 4] ?? '-';
        echo '-';
        echo $data[4 * $this->tubeSize + 5] ?? '-';
        echo $data[4 * $this->tubeSize + 6] ?? '-';
        echo "\n";

        for($r = $this->tubeSize - 1; $r>=0; $r--)
        {
            echo "  ";
            echo $data[0 * $this->tubeSize + $r] ?? '-';
            echo " ";
            echo $data[1 * $this->tubeSize + $r] ?? '-';
            echo " ";
            echo $data[2 * $this->tubeSize + $r] ?? '-';
            echo " ";
            echo $data[3 * $this->tubeSize + $r] ?? '-';
            echo "\n";
        }
    }

    protected function letterFactor($letter)
    {
        if($letter === 'A') return 1;
        else if($letter === 'B') return 10;
        else if($letter === 'C') return 100;
        else if($letter === 'D') return 1000;
        else return null;
    }

    protected function checkExit()
    {
        $this->numExplores++;
        if($this->numExplores >= $this->maxExplores){
            return true;
        }
        return false;
    }
}
