<?php

namespace Yalit\TwigHeroiconBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconSize;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconType;
use Yalit\TwigHeroiconBundle\Services\HeroiconGetterInterface;

class TwigHeroiconExtension extends AbstractExtension
{
    public function __construct(
        private readonly HeroiconGetterInterface $heroiconGetter,
        private readonly string $defaultDisplayType = 'outline',
        private readonly string $defaultSize = '24'
    ) {}

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('heroicon', $this->getHeroicon(...), ['is_safe' => ['html']])
        ];
    }

    public function getHeroicon(string $name, string $type = 'outline', string $size = '24', string $className = ''): string
    {
        $heroiconType = $type === '' ? HeroiconType::from($this->defaultDisplayType) : HeroiconType::from($type);
        $heroiconSize = $size === '' ? HeroiconSize::from($this->defaultSize) : HeroiconSize::from($size);

        return $this->heroiconGetter->getHeroicon($name, $heroiconType, $heroiconSize, $className);
    }
}
