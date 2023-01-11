<?php

namespace App\Service;

use App\Entity\Comment;

class CommentAverage
{
public function calculate(array $rates):float
{
return (array_sum($rates) / count($rates));
}
}