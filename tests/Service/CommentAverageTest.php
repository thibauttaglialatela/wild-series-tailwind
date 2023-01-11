<?php
namespace App\Tests\Service;
use App\Entity\Comment;
use App\Service\CommentAverage;
use PHPUnit\Framework\TestCase;

final class CommentAverageTest extends TestCase
{
    public function testCalculate():void
    {
        $commentAverage = new CommentAverage();
        $this->assertSame(5.2, $commentAverage->calculate([7, 4, 10, 5, 0]), 'erreur');
        $this->assertSame(4.25, $commentAverage->calculate([3, 5, 2, 7]), 'erreur');
        $this->assertSame(7.6, $commentAverage->calculate([9, 8, 7, 8, 6]), 'erreur');
        $this->assertSame(0.0, $commentAverage->calculate([]), 'erreur');
    }
}