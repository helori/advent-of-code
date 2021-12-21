<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_21 extends Aoc
{
    protected $player1At = null;
    protected $player2At = null;

    protected $player1Score = 0;
    protected $player2Score = 0;

    protected $dice = 0;
    protected $diceRolled = 0;

    protected function init()
    {
        $this->player1Score = 0;
        $this->player2Score = 0;

        $this->dice = 0;
        $this->diceRolled = 0;
    }

    protected function runPart1()
    {
        $this->player1At = 4;
        $this->player2At = 2;

        $winScore = 1000;

        while($this->player1Score < $winScore && $this->player2Score < $winScore)
        {
            $spaces = $this->rollDice3();
            $this->player1At = $this->movePawn($this->player1At, $spaces);
            $this->player1Score += $this->player1At;

            if($this->player1Score < $winScore)
            {
                $spaces = $this->rollDice3();
                $this->player2At = $this->movePawn($this->player2At, $spaces);
                $this->player2Score += $this->player2At;
            }
        }

        return ($this->player2Score * $this->diceRolled);
    }

    protected function movePawn($position, $spaces)
    {
        $spaces = ($spaces % 10);
        $newPosition = ($position + $spaces) % 10;
        $newPosition = ($newPosition === 0) ? 10 : $newPosition;
        return $newPosition;
    }

    protected function rollDice3()
    {
        $spaces = $this->rollDice();
        $spaces += $this->rollDice();
        $spaces += $this->rollDice();

        return $spaces;
    }

    protected function rollDice()
    {
        if($this->dice === 100){
            $this->dice = 1;
        }else{
            $this->dice += 1;
        }
        $this->diceRolled += 1;
        return $this->dice;
    }

    protected function runPart2()
    {
        $universes = [
            '4_2_0_0' => 1,
        ];

        $allEnded = false;

        while(!$allEnded)
        {
            $universes = $this->rollQuanticDice($universes, 0);
            $universes = $this->rollQuanticDice($universes, 0);
            $universes = $this->rollQuanticDice($universes, 0);

            $universes = $this->computeScore($universes, 0);
            $allEnded = $this->checkEnded($universes);

            if(!$allEnded)
            {
                $universes = $this->rollQuanticDice($universes, 1);
                $universes = $this->rollQuanticDice($universes, 1);
                $universes = $this->rollQuanticDice($universes, 1);

                $universes = $this->computeScore($universes, 1);
                $allEnded = $this->checkEnded($universes);
            }
        }

        //dd($universes);

        $winning1 = 0;
        $winning2 = 0;

        foreach($universes as $key => $count)
        {
            $keyParts = explode('_', $key);
            $score1 = intVal($keyParts[2]);
            $score2 = intVal($keyParts[3]);

            if($score1 >= 21)
            {
                $winning1 += $count;
            }
            if($score2 >= 21)
            {
                $winning2 += $count;
            }
        }

        return max($winning1, $winning2);
    }

    protected function rollQuanticDice(&$universes, $playerIdx)
    {
        $newUniverses = $universes;
        foreach($universes as $key => $count)
        {
            $parts = explode('_', $key);
            $sc1 = intVal($parts[2]);
            $sc2 = intVal($parts[3]);

            if($sc1 < 21 && $sc2 < 21)
            {
                $keyMoved = $this->moveKey($key, 1, $playerIdx);
                $this->incrementKey($newUniverses, $keyMoved, $count);

                $keyMoved = $this->moveKey($key, 2, $playerIdx);
                $this->incrementKey($newUniverses, $keyMoved, $count);

                $keyMoved = $this->moveKey($key, 3, $playerIdx);
                $this->incrementKey($newUniverses, $keyMoved, $count);

                $newUniverses[$key] -= $count;
            }
        }
        return array_filter($newUniverses);
    }

    protected function incrementKey(&$universes, $key, $count)
    {
        if(!isset($universes[$key])){
            $universes[$key] = 0;
        }
        $universes[$key] += $count;
    }

    protected function moveKey($key, $spaces, $playerIdx)
    {
        $keyParts = explode('_', $key);
        $at = intVal($keyParts[$playerIdx]);

        $at1 = $this->movePawn($at, $spaces);
        $keyParts[$playerIdx] = $at1;
        $key1 = implode('_', $keyParts);

        return $key1;
    }

    protected function computeScore(&$universes, $playerIdx)
    {
        $newUniverses = $universes;
        foreach($universes as $key => $count)
        {
            $keyParts = explode('_', $key);
            $score1 = intVal($keyParts[2]);
            $score2 = intVal($keyParts[3]);

            if($score1 < 21 && $score2 < 21)
            {
                $score = intVal($keyParts[$playerIdx + 2]);
                $at = intVal($keyParts[$playerIdx]);
                $keyParts[$playerIdx + 2] = $score + $at;
                $newKey = implode('_', $keyParts);

                $this->incrementKey($newUniverses, $newKey, $count);
                $newUniverses[$key] -= $count;
            }
        }
        return array_filter($newUniverses);
    }

    protected function checkEnded(&$universes)
    {
        $allEnded = true;
        foreach($universes as $key => $count)
        {
            $keyParts = explode('_', $key);
            $score1 = intVal($keyParts[2]);
            $score2 = intVal($keyParts[3]);

            $universeEnded = ($score1 >= 21) || ($score2 >= 21);
            $allEnded &= $universeEnded;
        }
        return $allEnded;
    }
}
