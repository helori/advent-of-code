<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Day_19 extends Aoc
{
    protected $scanners = [];
    protected $pairs = [];

    protected function init()
    {
        $this->scanners = [];
        $scanner = [];

        foreach($this->lines as $line)
        {
            if(empty($line))
            {
                $this->scanners[] = $scanner;
                $scanner = [];
            }
            else if(!Str::startsWith($line, '---'))
            {
                $scanner[] = $this->toInts(explode(',', $line));
            }
        }

        $this->readPairs();
        //dump($this->pairs);
    }

    protected function runPart1()
    {
        $beacons = $this->allBeaconsForIdx([
            'idx' => 0,
            'exceptIndexes' => [0],
            'shift' => [0, 0, 0],
            'orientation' => [1, 2, 3],
        ]);

        //$this->display($beacons);


        $beaconsKeys = [];
        foreach($beacons as $beacon)
        {
            $key = $beacon[0].'_'.$beacon[1].'_'.$beacon[2];
            $beaconsKeys[] = $key;
        }
        $beaconsKeys = array_unique($beaconsKeys);
        return count($beaconsKeys);
    }

    protected function allBeaconsForIdx($data)
    {
        $scanner = $this->scanners[$data['idx']];
        $scanner = $this->orientedScanner($scanner, $data['orientation']);
        
        $beacons = [];

        // On exprime les beacons du scanner dans les coordonnées du scanner 1
        foreach($scanner as $beacon)
        {
            $pos = [
                $beacon[0] + $data['shift'][0],
                $beacon[1] + $data['shift'][1],
                $beacon[2] + $data['shift'][2],
            ];
            $beacons[] = $pos;
        }

        foreach($this->pairs as $pair)
        {
            if($pair['scanIdx1'] === $data['idx'] && !in_array($pair['scanIdx2'], $data['exceptIndexes']))
            {
                $exceptIndexes = $data['exceptIndexes'];
                $exceptIndexes[] = $pair['scanIdx2'];

                $subbeacons = $this->allBeaconsForIdx([
                    'idx' => $pair['scanIdx2'],
                    'exceptIndexes' => $exceptIndexes,
                    'shift' => $pair['shift'],
                    'orientation' => $pair['orientation'],
                ]);

                $subbeacons = $this->orientedScanner($subbeacons, $data['orientation']);
                foreach($subbeacons as $subbeacon)
                {
                    $pos = [
                        $subbeacon[0] + $data['shift'][0],
                        $subbeacon[1] + $data['shift'][1],
                        $subbeacon[2] + $data['shift'][2],
                    ];
                    $beacons[] = $pos;
                }
            }
        }
        return $beacons;
    }

    protected function display($beacons)
    {
        foreach($beacons as $beacon)
        {
            dump(implode(',', $beacon));
        }
    }

    protected function runPart2()
    {
        $centers = $this->allCentersForIdx([
            'idx' => 0,
            'exceptIndexes' => [0],
            'shift' => [0, 0, 0],
            'orientation' => [1, 2, 3],
        ]);


        $maxM = 0;
        for($i = 0; $i < count($centers); ++$i)
        {
            for($j = $i+1; $j < count($centers); ++$j)
            {
                $c1 = $centers[$i];
                $c2 = $centers[$j];

                $m = abs($c1[0] - $c2[0]) + abs($c1[1] - $c2[1]) + abs($c1[2] - $c2[2]);
                $maxM = max($maxM, $m);
            }
        }

        return $maxM;
    }

    protected function allCentersForIdx($data)
    {
        $scanner = $this->scanners[$data['idx']];
        $scanner = $this->orientedScanner($scanner, $data['orientation']);
        
        $centers = [];

        $pos = [
            $data['shift'][0],
            $data['shift'][1],
            $data['shift'][2],
        ];
        $centers[] = $pos;

        foreach($this->pairs as $pair)
        {
            if($pair['scanIdx1'] === $data['idx'] && !in_array($pair['scanIdx2'], $data['exceptIndexes']))
            {
                $exceptIndexes = $data['exceptIndexes'];
                $exceptIndexes[] = $pair['scanIdx2'];

                $subcenters = $this->allCentersForIdx([
                    'idx' => $pair['scanIdx2'],
                    'exceptIndexes' => $exceptIndexes,
                    'shift' => $pair['shift'],
                    'orientation' => $pair['orientation'],
                ]);

                $subcenters = $this->orientedScanner($subcenters, $data['orientation']);
                foreach($subcenters as $subcenter)
                {
                    $pos = [
                        $subcenter[0] + $data['shift'][0],
                        $subcenter[1] + $data['shift'][1],
                        $subcenter[2] + $data['shift'][2],
                    ];
                    $centers[] = $pos;
                }
            }
        }
        return $centers;
    }

    protected function readPairs()
    {
        $pairsFile = dirname(__DIR__).'/files/Day_19_pairs.json';
        $pairs = [];

        if(!file_exists($pairsFile))
        {
            $numScans = count($this->scanners);
            for($i = 0; $i < $numScans; ++$i)
            {
                for($j = 0; $j < $numScans; ++$j)
                {
                    if($i !== $j)
                    {
                        $overlaps = $this->overlaps($this->scanners[$i], $this->scanners[$j]);
                        dump("=> Overlaps between $i and $j : ".$overlaps['count']);
                        if($overlaps['count'] >= 12)
                        {
                            $pairs[] = array_merge($overlaps, [
                                'scanIdx1' => $i,
                                'scanIdx2' => $j,
                            ]);
                        }
                    }
                }
            }
            file_put_contents($pairsFile, json_encode($pairs));
        }
        else
        {
            $pairs = json_decode(file_get_contents($pairsFile), true);
        }
        $this->pairs = $pairs;
    }
    
    protected function overlaps($scanner1, $scanner2)
    {
        $orientations = $this->orientations3d();
        $oriented1 = $this->orientedScanner($scanner1, $orientations[0]);

        $maxOverlaps = [
            'count' => 0,
        ];

        foreach($orientations as $i => $orientation)
        {
            $oriented2 = $this->orientedScanner($scanner2, $orientation);

            $common = $this->getCommonBeacons($oriented1, $oriented2);
            //dump("- 2nd scanner oriented $i overlaps : ".$common);
            if($common['count'] > $maxOverlaps['count'])
            {
                $maxOverlaps['count'] = $common['count'];
                $maxOverlaps['orientation'] = $orientation;
                $maxOverlaps['shift'] = $common['shift'];
                $maxOverlaps['common_beacons'] = $common['common_beacons'];
            }
        }

        return $maxOverlaps;
    }

    protected function getCommonBeacons($oriented1, $oriented2)
    {
        $maxCommon = [
            'count' => 0,
        ];

        // Pour chaque beacon du premier scanner
        foreach($oriented1 as $beacon1)
        {
            // Pour chaque beacon du second scanner
            foreach($oriented2 as $beacon2)
            {
                // On calcule l'écart entre les 2 scanners
                // (En considérant que les beacons se superposent)
                $shift = [
                    $beacon1[0] - $beacon2[0],
                    $beacon1[1] - $beacon2[1],
                    $beacon1[2] - $beacon2[2]
                ];

                // On va calculer le nombre de beacons vus en commun
                $commonBeacons = [];

                // On parcourt tous les beacons du scanner 2
                foreach($oriented2 as $b2)
                {
                    // On exprime le beacon du scanner 2 dans les coordonnées du 1
                    $pos2 = [
                        $b2[0] + $shift[0],
                        $b2[1] + $shift[1],
                        $b2[2] + $shift[2],
                    ];

                    // On parcourt tous les beacons du scanner 1
                    foreach($oriented1 as $b1)
                    {
                        // On regarde s'il y a recouvrement
                        if(($b1[0] === $pos2[0]) &&
                            ($b1[1] === $pos2[1]) &&
                            ($b1[2] === $pos2[2])){

                            $commonBeacons[] = $b1;
                        }
                    }
                }
                
                if(count($commonBeacons) > $maxCommon['count'])
                {
                    $maxCommon['common_beacons'] = $commonBeacons;
                    $maxCommon['count'] = count($commonBeacons);
                    $maxCommon['shift'] = $shift;
                }
            }
        }
        return $maxCommon;
    }

    protected function orientations3d()
    {
        return [
            [1, 2, 3],
            [1, -3, 2],
            [1, -2, -3],
            [1, 3, -2],

            [3, 1, 2],
            [2, 1, -3],
            [-3, 1, -2],
            [-2, 1, 3],

            [2, 3, 1],
            [-3, 2, 1],
            [-2, -3, 1],
            [3, -2, 1],

            [-1, 2, -3],
            [-1, -3, -2],
            [-1, -2, 3],
            [-1, 3, 2],

            [-3, -1, 2],
            [-2, -1, -3],
            [3, -1, -2],
            [2, -1, 3],

            [2, -3, -1],
            [-3, -2, -1],
            [-2, 3, -1],
            [3, 2, -1],
        ];
    }

    protected function orientedScanner($scanner, array $orientation)
    {
        $beacons = [];
        foreach($scanner as $beacon)
        {
            $orientedBeacon = [];
            foreach($orientation as $dim)
            {
                $orientedBeacon[] = $this->valueFormDim($beacon, $dim);
            }
            $beacons[] = $orientedBeacon;
        }
        return $beacons;
    }

    protected function valueFormDim($beacon, $dim)
    {
        if($dim === 1) return $beacon[0];
        else if($dim === -1) return -$beacon[0];
        else if($dim === 2) return $beacon[1];
        else if($dim === -2) return -$beacon[1];
        else if($dim === 3) return $beacon[2];
        else if($dim === -3) return -$beacon[2];
        return null;
    }
}
