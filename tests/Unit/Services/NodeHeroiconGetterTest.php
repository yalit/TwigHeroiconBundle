<?php

namespace Services;

use PHPUnit\Framework\TestCase;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconSize;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconType;
use Yalit\TwigHeroiconBundle\Services\NodeHeroiconGetter;

class NodeHeroiconGetterTest extends TestCase
{
    /**
     * @dataProvider getTypeAndSizes
     * @test
     */
    public function successfulWithNoClassName(string $type, string $size): void
    {
        $heroiconGetter = new NodeHeroiconGetter(getcwd().'/tests');

        $svg = $heroiconGetter->getHeroicon('test', HeroiconType::from($type), HeroiconSize::from($size), '');
        $this->assertStringContainsString(sprintf('viewBox="0 0 %s %s"', $size, $size), $svg);

        $typeStringSpecific = $type === 'outline' ? 'stroke-linecap="round"' : 'fill-rule="evenodd"';
        $this->assertStringContainsString($typeStringSpecific, $svg);
    }

    /**
     * @dataProvider getTypeAndSizes
     * @test
     */
    public function successfulWithClassName(string $type, string $size): void
    {
        $heroiconGetter = new NodeHeroiconGetter(getcwd().'/tests');

        $className = 'icon h-4 w-4 rounded stroke-blue-800';
        $svg = $heroiconGetter->getHeroicon('test', HeroiconType::from($type), HeroiconSize::from($size), $className);
        $this->assertStringContainsString(sprintf('viewBox="0 0 %s %s"', $size, $size), $svg);

        $typeStringSpecific = $type === 'outline' ? 'stroke-linecap="round"' : 'fill-rule="evenodd"';
        $this->assertStringContainsString($typeStringSpecific, $svg);

        $this->assertStringContainsString(sprintf('class="%s"', $className), $svg);
    }

    /**
     * @return iterable<array<string,string>>
     */
    public static function getTypeAndSizes(): iterable
    {
        yield 'Outline 24' => ['type' => 'outline', 'size' => '24'];
        yield 'Solid 24' => ['type' => 'solid', 'size' => '24'];
        yield 'Solid 20' => ['type' => 'solid', 'size' => '20'];
        yield 'Solid 16' => ['type' => 'solid', 'size' => '16'];
    }
}
