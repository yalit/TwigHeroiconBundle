<?php

namespace TwigHeroiconBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TwigHeroiconBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
