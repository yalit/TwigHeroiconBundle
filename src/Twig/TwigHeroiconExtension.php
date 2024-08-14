<?php

namespace Yalit\TwigHeroiconBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigHeroiconExtension extends AbstractExtension
{
    public function __construct(
        private readonly string $twigHeroiconPublicDir,
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
        try {
            $svg = file_get_contents($this->twigHeroiconPublicDir . '/build/heroicons/' . $name . '.svg');
            return $svg;
        } catch (\Exception $e) {
            return 'None';
        }
    }
}
