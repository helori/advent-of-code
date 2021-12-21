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
            $this->player1At = $this->movePawn($this->player1At);
            $this->player1Score += $this->player1At;

            if($this->player1Score < $winScore)
            {
                $this->player2At = $this->movePawn($this->player2At);
                $this->player2Score += $this->player2At;
            }
        }

        return ($this->player2Score * $this->diceRolled);
    }

    protected function movePawn($position)
    {
        $spaces = $this->rollDice3();
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
        return 0;
    }
}
