<?php

namespace App\Components;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('_card')]
class CardComponent
{
    public string $cardTitle;
    public string $cardText;
    public string $cardPosterUrl;
}