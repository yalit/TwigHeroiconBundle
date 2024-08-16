<?php

namespace Yalit\TwigHeroiconBundle\Services;

use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconSize;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconType;

class NodeHeroiconGetter implements HeroiconGetterInterface
{
    public function __construct(
        private readonly string $rootAppDir,
    ) {}

    public function getHeroicon(string $name, HeroiconType $type, HeroiconSize $size, string $className): string
    {
        $heroiconFileName = implode('/', [$size->value, $type->value, $name]) . '.svg';
        $svg = file_get_contents($this->rootAppDir . '/node_modules/heroicons/' . $heroiconFileName);

        // insert the class value if it's not empty
        return $className !== '' ? str_replace('<svg', sprintf('<svg class="%s"', $className), $svg) : $svg;
    }
}
