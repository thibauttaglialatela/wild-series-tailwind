<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('_card_program')]
class CardProgramComponent
{
    public ?string $cardPosterUrl;
    public ?string $imgAlt;
    public ?string $programTitle;
    public ?string $programSynopsis;
    public ?string $programPoster;
    public string $programSlug;
}