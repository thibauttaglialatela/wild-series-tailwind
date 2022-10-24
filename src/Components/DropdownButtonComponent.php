<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('_dropdown_button')]
class DropdownButtonComponent
{
    public string $buttonText;
    public string $buttonUrl;
    public string $linkText;
}