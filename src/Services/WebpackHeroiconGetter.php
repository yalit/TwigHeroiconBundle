<?php

namespace Yalit\TwigHeroiconBundle\Services;

use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconSize;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconType;

class WebpackHeroiconGetter implements HeroiconGetterInterface
{
    public function __construct(
        private readonly string $twigHeroiconPublicDir,
    ) {}

    public function getHeroicon(string $name, HeroiconType $type, HeroiconSize $size, string $className): string
    {
        $heroiconFileName = implode('-', [$name, $type->value, $size->value]) . '.svg';
        $svg = file_get_contents($this->twigHeroiconPublicDir . '/build/heroicons/' . $heroiconFileName);

        // insert the class value if it's not empty
        return $className !== '' ? str_replace('<svg', sprintf('<svg class="%s"', $className), $svg) : $svg;
    }
}
