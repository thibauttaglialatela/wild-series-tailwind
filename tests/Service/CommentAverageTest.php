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
        $this->assertSame(5.2, $commentAverage->calculate(), 'erreur');
    }
}