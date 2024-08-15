<?php

namespace Yalit\TwigHeroiconBundle\Services;

interface HeroiconGetterInterface
{
    public function getHeroicon(string $name, string $type, string $size, string $className): string;
}
