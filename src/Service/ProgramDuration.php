<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $numberOfSeasons = $program->getSeasons()->count();
        $totalWatchingTime = 0;
        for ($i = 0; $i < $numberOfSeasons; $i++) {
            for ($j = 0; $j < $program->getSeasons()[$i]->getEpisodes()->count(); $j++) {
                $totalWatchingTime += $program->getSeasons()[$i]->getEpisodes()[$j]->getDuration();
            }
        }
        if (!isset($days)) {
            return "Aucune saison pour le moment";
        }
        return $this->convertMinToDays($totalWatchingTime);
    }

    public function convertMinToDays($min)
    {
        $hours = str_pad(floor($min / 60), 2, "0", STR_PAD_LEFT);
        $mins = str_pad($min % 60, 2, "0", STR_PAD_LEFT);

        if ((int)$hours > 24) {
            $days = str_pad(floor($hours / 24), 2, "0", STR_PAD_LEFT);
            $hours = str_pad($hours % 24, 2, "0", STR_PAD_LEFT);
        }
        if (isset($days)) {
            $days = $days . " jour(s) ";
        }

        return $days . $hours . " Heure(s) " . $mins . " minute(s)";
    }
}