<?php declare(strict_types=1);

namespace Yalit\TwigHeroiconBundle\Tests\Unit\Twig;

use PHPUnit\Framework\TestCase;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconSize;
use Yalit\TwigHeroiconBundle\Services\WebpackHeroiconGetter;
use Yalit\TwigHeroiconBundle\Twig\TwigHeroiconExtension;

class TwigHeroiconExtensionTest extends TestCase
{
    /**
     * @test
     */
    public function successfulGetHeroiconWithOnlyName(): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public/build');
        $twigExtension = new TwigHeroiconExtension($heroiconGetter);

        $svg = $twigExtension->getHeroicon('test');
        $this->assertStringContainsString(sprintf('viewBox="0 0 %s %s"', HeroiconSize::TWENTY_FOUR->value, HeroiconSize::TWENTY_FOUR->value), $svg);
        $this->assertStringContainsString('stroke-linecap="round"', $svg);  // default is outline
    }

    /**
     * @test
     * @dataProvider getTypeAndSizes
     */
    public function successfulGetHeroiconWithNameAndType(string $type, string $size): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public/build');
        $twigExtension = new TwigHeroiconExtension($heroiconGetter);

        $svg = $twigExtension->getHeroicon('test', $type);
        $this->assertStringContainsString(sprintf('viewBox="0 0 %s %s"', HeroiconSize::TWENTY_FOUR->value, HeroiconSize::TWENTY_FOUR->value), $svg);

        $typeStringSpecific = $type === 'outline' ? 'stroke-linecap="round"' : 'fill-rule="evenodd"';
        $this->assertStringContainsString($typeStringSpecific, $svg);
    }

    /**
     * @test
     * @dataProvider getTypeAndSizes
     */
    public function successfulGetHeroiconWithNameAndTypeAndSizes(string $type, string $size): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public/build');
        $twigExtension = new TwigHeroiconExtension($heroiconGetter);

        $svg = $twigExtension->getHeroicon('test', $type, $size);
        $this->assertStringContainsString(sprintf('viewBox="0 0 %s %s"', $size, $size), $svg);

        $typeStringSpecific = $type === 'outline' ? 'stroke-linecap="round"' : 'fill-rule="evenodd"';
        $this->assertStringContainsString($typeStringSpecific, $svg);
    }

    /**
     * @test
     * @dataProvider getTypeAndSizes
     */
    public function successfulGetHeroiconWithNameAndTypeAndSizesWithClassName(string $type, string $size): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public/build');
        $twigExtension = new TwigHeroiconExtension($heroiconGetter);

        $className = 'icon h-4 w-4 rounded stroke-blue-800';
        $svg = $twigExtension->getHeroicon('test', $type, $size, $className);
        $this->assertStringContainsString(sprintf('viewBox="0 0 %s %s"', $size, $size), $svg);

        $typeStringSpecific = $type === 'outline' ? 'stroke-linecap="round"' : 'fill-rule="evenodd"';
        $this->assertStringContainsString($typeStringSpecific, $svg);
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
