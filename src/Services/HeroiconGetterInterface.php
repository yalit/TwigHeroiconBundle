<?php

namespace Yalit\TwigHeroiconBundle\Services;

use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconSize;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconType;

interface HeroiconGetterInterface
{
    public function getHeroicon(string $name, HeroiconType $type, HeroiconSize $size, string $className): string;
}
