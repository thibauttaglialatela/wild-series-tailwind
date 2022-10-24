<?php

namespace App\Components;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('_button')]
class ButtonComponent
{
    public string $buttonText;
    public string $buttonUrl;
}