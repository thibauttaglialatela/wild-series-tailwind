<?php

namespace App\Service;

use App\Entity\Comment;

class CommentAverage
{
public function calculate(array $rates):float
{
    if(count($rates) === 0) {
        return 0.0;
    }
    $average = (array_sum($rates) / count($rates));
return round($average, 2);
}
}