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
            [
                'p' => [
                    [
                        'at' => 4,
                        'sc' => 0,
                    ],
                    [
                        'at' => 8,
                        'sc' => 0,
                    ],
                ],
                'e' => false,
            ],
        ];

        $winScore = 21;
        $allEnded = false;

        while(!$allEnded)
        {
            $universes = $this->rollQuanticDice($universes, 0);
            $universes = $this->rollQuanticDice($universes, 0);
            $universes = $this->rollQuanticDice($universes, 0);

            $this->computeScores($universes, 0);
            $allEnded = $this->checkEnded($universes);

            if(!$allEnded)
            {
                $universes = $this->rollQuanticDice($universes, 1);
                $universes = $this->rollQuanticDice($universes, 1);
                $universes = $this->rollQuanticDice($universes, 1);

                $this->computeScores($universes, 1);
                $allEnded = $this->checkEnded($universes);
            }
        }

        $winning1 = count(Arr::where($universes, function($universe){
            return $universe['p'][0]['sc'] >= 21;
        }));

        $winning2 = count(Arr::where($universes, function($universe){
            return $universe['p'][1]['sc'] >= 21;
        }));

        return ($winning1 > $winning2) ? $winning1 : $winning2;
    }

    protected function checkEnded(&$universes)
    {
        $ended = true;
        foreach($universes as $universe)
        {
            $ended &= $universe['e'];
        }
        return $ended;
    }

    protected function rollQuanticDice(&$universes, $playerIdx)
    {
        $newUniverses = [];
        foreach($universes as $universe)
        {
            if(!$universe['e'])
            {
                $newUniverses[] = $this->createUniverse($universe, 1, $playerIdx);
                $newUniverses[] = $this->createUniverse($universe, 2, $playerIdx);
                $newUniverses[] = $this->createUniverse($universe, 3, $playerIdx);
            }
            else
            {
                $newUniverses[] = $universe;
            }
        }
        return $newUniverses;
    }

    protected function createUniverse($baseUniverse, $spaces, $playerIdx)
    {
        $at = $baseUniverse['p'][$playerIdx]['at'];
        $at = $this->movePawn($at, $spaces);
        
        $universe = $baseUniverse;
        $universe['p'][$playerIdx]['at'] = $at;

        return $universe;
    }

    protected function computeScores(&$universes, $playerIdx)
    {
        foreach($universes as &$universe)
        {
            $this->computeScore($universe, $playerIdx);
            $universe['e'] = ($universe['p'][$playerIdx]['sc'] >= 21);
        }
    }

    protected function computeScore(&$universe, $playerIdx)
    {
        $universe['p'][$playerIdx]['sc'] += $universe['p'][$playerIdx]['at'];
    }
}
