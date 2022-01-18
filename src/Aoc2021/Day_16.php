<?php

namespace Aoc\Aoc2021;

use Aoc\Aoc;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_16 extends Aoc
{
    protected $hex = null;
    protected $bin = null;

    protected function init()
    {
        $this->hex = trim($this->lines[0]);
        $this->bin = $this->toBin($this->hex);
        //$this->dump($this->hex);
        //$this->dump($this->bin);
    }

    protected function toBin($hex)
    {
        $digits = str_split($hex);
        $bin = '';
        foreach($digits as $digit){
            $bin .= str_pad(base_convert($digit, 16, 2), 4, '0', STR_PAD_LEFT);
        }
        return $bin;
    }

    protected function parsePacket($bin)
    {
        //echo "----------\n";
        //echo "Packet : ".$bin."\n";

        $version = substr($bin, 0, 3);
        $typeId = substr($bin, 3, 3);

        $version = intVal(base_convert($version, 2, 10));
        $typeId = intVal(base_convert($typeId, 2, 10));

        $type = null;
        $number = null;
        $lengthTypeId = null;
        $subpacketsLength = null;
        $subpacketsCount = null;
        $subpacketsData = null;
        $subpackets = [];
        $remainingData = null;
        $operator = null;
        $value = null;

        if($typeId === 4)
        {
            $type = 'litteral';
            $end = false;
            $pos = 6;
            $number = '';
            while(!$end)
            {
                $part = substr($bin, $pos, 5);
                $start = substr($part, 0, 1);
                $number .= substr($part, 1, 4);
                $pos += 5;
                if($start === '0'){
                    $end = true;
                }
            }
            $number = intVal(base_convert($number, 2, 10));
            $remainingData = substr($bin, $pos);
        }
        else
        {
            $type = 'operator';
            $lengthTypeId = intVal(substr($bin, 6, 1));
            if($lengthTypeId === 0)
            {
                $subpacketsLength = substr($bin, 7, 15);
                $subpacketsLength = intVal(base_convert($subpacketsLength, 2, 10));
                $subpacketsData = substr($bin, 7 + 15, $subpacketsLength);
                $remainingData = substr($bin, 7 + 15 + $subpacketsLength);
            }
            else if($lengthTypeId === 1)
            {
                $subpacketsCount = substr($bin, 7, 11);
                $subpacketsCount = intVal(base_convert($subpacketsCount, 2, 10));
                $subpacketsData = substr($bin, 7 + 11);
            }

            // Operator types
            if($typeId === 0){
                $operator = 'sum';
            }else if($typeId === 1){
                $operator = 'product';
            }else if($typeId === 2){
                $operator = 'min';
            }else if($typeId === 3){
                $operator = 'max';
            }else if($typeId === 5){
                $operator = 'gt';
            }else if($typeId === 6){
                $operator = 'lt';
            }else if($typeId === 7){
                $operator = 'equal';
            }
        }

        if(!is_null($subpacketsLength))
        {
            $rem = $subpacketsData;
            while(strlen($rem) > 0){
                $subpacket = $this->parsePacket($rem);
                $rem = $subpacket['remainingData'];
                $subpackets[] = $subpacket;
            }
        }
        else if(!is_null($subpacketsCount))
        {
            $rem = $subpacketsData;
            for($i=0; $i<$subpacketsCount; ++$i)
            {
                $subpacket = $this->parsePacket($rem);
                $rem = $subpacket['remainingData'];
                $subpackets[] = $subpacket;
            }
            $remainingData = $rem;
        }
        
        return [
            'data' => $bin,
            'version' => $version,
            'typeId' => $typeId,
            'type' => $type,
            'operator' => $operator,
            'number' => $number,
            'value' => $value,
            'lengthTypeId' => $lengthTypeId,
            'subpacketsLength' => $subpacketsLength,
            'subpacketsCount' => $subpacketsCount,
            'subpacketsData' => $subpacketsData,
            'remainingData' => $remainingData,
            'subpackets' => $subpackets,
        ];
    }

    protected function sumPacketsVersion($packets, &$sum)
    {
        foreach($packets as $packet)
        {
            $sum += $packet['version'];
            $this->sumPacketsVersion($packet['subpackets'], $sum);
        }
    }

    protected function runPart1()
    {
        //echo "Hexa : ".$this->hex."\n";
        $packets = [$this->parsePacket($this->bin)];

        $sum = 0;
        $this->sumPacketsVersion($packets, $sum);

        return $sum;
    }

    protected function packetValue($packet)
    {
        $op = $packet['operator'];
        $value = null;

        if($op === null){
            return $packet['number'];
        }
        else if($op === 'sum')
        {
            $value = 0;
            foreach($packet['subpackets'] as $subpacket)
            {
                $value += $this->packetValue($subpacket);
            }
        }
        else if($op === 'product')
        {
            $value = 1;
            foreach($packet['subpackets'] as $subpacket)
            {
                $value *= $this->packetValue($subpacket);
            }
        }
        else if($op === 'max')
        {
            $values = [];
            foreach($packet['subpackets'] as $subpacket)
            {
                $values[] = $this->packetValue($subpacket);
            }
            $value = max($values);
        }
        else if($op === 'min')
        {
            $values = [];
            foreach($packet['subpackets'] as $subpacket)
            {
                $values[] = $this->packetValue($subpacket);
            }
            $value = min($values);
        }
        else if($op === 'gt')
        {
            $value1 = $this->packetValue($packet['subpackets'][0]);
            $value2 = $this->packetValue($packet['subpackets'][1]);
            $value = ($value1 > $value2);
        }
        else if($op === 'lt')
        {
            $value1 = $this->packetValue($packet['subpackets'][0]);
            $value2 = $this->packetValue($packet['subpackets'][1]);
            $value = ($value1 < $value2);
        }
        else if($op === 'equal')
        {
            $value1 = $this->packetValue($packet['subpackets'][0]);
            $value2 = $this->packetValue($packet['subpackets'][1]);
            $value = ($value1 == $value2);
        }
        return $value;
    }

    protected function runPart2()
    {
        $packet = $this->parsePacket($this->bin);
        return $this->packetValue($packet);
    }
}
