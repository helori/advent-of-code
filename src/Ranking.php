<?php

namespace Aoc;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Carbon\Carbon;


class Ranking
{
    public function getLeaderboard()
    {
        $client = new Client();
        $jar = CookieJar::fromArray(
            [
                'session' => trim(file_get_contents(dirname(__DIR__).'/token')),
            ],
            'adventofcode.com'
        );
        
        $response = $client->request('GET', 'https://adventofcode.com/2021/leaderboard/private/view/629107.json', [
            'cookies' => $jar
        ]);

        $body = $response->getBody();
        return json_decode($body, true);
    }

    public function getStats()
    {
        $data = $this->getLeaderboard();

        $members = [];

        foreach($data['members'] as $member)
        {
            $minTime = 9999999999;
            $minDay = null;

            $diffTime = 9999999999;
            $diffDay = null;

            $diffSum = 0;
            $diffDays = 0;

            foreach($member['completion_day_level'] as $d => $day){
                foreach($day as $star => $starData){
                    $timestamp = intVal($starData['get_star_ts']);
                    $time = Carbon::createFromTimestamp($timestamp);
                    $start = Carbon::create(2021, 12, $d, 6, 0, 0)->setTimezone('Europe/Paris')->setHour(6);
                    $seconds = $start->diffInSeconds($time);
                    if($seconds < $minTime)
                    {
                        $minTime = $seconds;
                        $minDay = $d;
                    }
                }
                if(count($day) === 2){
                    $t1 = Carbon::createFromTimestamp($day['1']['get_star_ts']);
                    $t2 = Carbon::createFromTimestamp($day['2']['get_star_ts']);
                    $seconds = $t1->diffInSeconds($t2);
                    if($seconds < $diffTime)
                    {
                        $diffTime = $seconds;
                        $diffDay = $d;
                    }
                    $diffSum += $seconds;
                    $diffDays++;
                }
            }
            
            $str = $this->secondsToStr($minTime);
            $members[] = [
                'name' => $member['name'],
                'min_time_seconds' => $minTime,
                'min_time_str' => $this->secondsToStr($minTime),
                'min_time_day' => $minDay,
                'diff_time_seconds' => $diffTime,
                'diff_time_str' => $this->secondsToStr($diffTime),
                'diff_time_day' => $diffDay,
                'diff_avg_seconds' => round($diffSum / $diffDays),
                'diff_avg_str' => $this->secondsToStr(round($diffSum / $diffDays)),
            ];
        }
        return $members;
    }

    public function rank(string $type)
    {
        $members = $this->getStats();

        if($type === "first-star-time")
        {
            usort($members, function($a, $b){
                return ($a['min_time_seconds'] < $b['min_time_seconds']) ? -1 : 1;
            });

            echo "---------------------------------------------------------------------\n";
            echo "Classement de ceux qui ont eu une 1ère étoile en un minimum de temps\n";
            echo "---------------------------------------------------------------------\n";
            foreach($members as $member)
            {
                echo $member['name']." : ".$member['min_time_str']." (Day ".$member['min_time_day'].")\n";
            }
        }
        else if($type === "second-star-avg-time")
        {
            usort($members, function($a, $b){
                return ($a['diff_avg_seconds'] < $b['diff_avg_seconds']) ? -1 : 1;
            });
            
            echo "---------------------------------------------------------------------\n";
            echo "Classement de ceux qui ont mis le moins de temps entre 2 étoiles\n";
            echo "---------------------------------------------------------------------\n";
            foreach($members as $member)
            {
                echo $member['name']." : ".$member['diff_avg_str']."\n";
            }
        }
    }

    public function secondsToStr($timeSeconds)
    {
        $hours = intVal(floor($timeSeconds / 3600));
        $minutes = intVal(floor(($timeSeconds - $hours * 3600) / 60));
        $seconds = $timeSeconds - $hours * 3600 - $minutes * 60;
        return $hours.":".str_pad($minutes, 2, '0', STR_PAD_LEFT).":".str_pad($seconds, 2, '0', STR_PAD_LEFT);
    }
}
