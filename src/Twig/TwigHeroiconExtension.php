<?php

namespace Yalit\TwigHeroiconBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Yalit\TwigHeroiconBundle\Services\HeroiconGetterInterface;

class TwigHeroiconExtension extends AbstractExtension
{
    public function __construct(
        private readonly HeroiconGetterInterface $heroiconGetter
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
        return $this->heroiconGetter->getHeroicon($name, $type, $size, $className);
    }
}
