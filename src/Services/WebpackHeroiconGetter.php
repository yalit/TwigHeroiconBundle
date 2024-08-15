<?php

namespace Yalit\TwigHeroiconBundle\Services;

use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconSizes;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconTypes;

class WebpackHeroiconGetter implements HeroiconGetterInterface
{
    public const TYPE_DEFAULT = HeroiconTypes::OUTLINE;
    public const SIZE_DEFAULT = HeroiconSizes::TWENTY_FOUR;

    public function __construct(
        private readonly string $twigHeroiconPublicDir,
    ) {}

    public function getHeroicon(string $name, string $type, string $size, string $className): string
    {
        $type = $type === '' ? self::TYPE_DEFAULT : HeroiconTypes::from($type);
        $size = $size === '' ? self::SIZE_DEFAULT : HeroiconSizes::from($size);

        $heroiconFileName = implode('-', [$name, $type->value, $size->value]) . '.svg';
        $svg = file_get_contents($this->twigHeroiconPublicDir . '/build/heroicons/' . $heroiconFileName);

        // insert the class value if it's not empty
        return $className !== '' ? str_replace('<svg', sprintf('<svg class="%s"', $className), $svg) : $svg;
    }
}
