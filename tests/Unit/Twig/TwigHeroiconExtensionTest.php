<?php declare(strict_types=1);

namespace Yalit\TwigHeroiconBundle\Tests\Unit\Twig;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Yalit\TwigHeroiconBundle\Services\Enum\HeroiconSizes;
use Yalit\TwigHeroiconBundle\Services\WebpackHeroiconGetter;
use Yalit\TwigHeroiconBundle\Twig\TwigHeroiconExtension;

class TwigHeroiconExtensionTest extends TestCase
{
    #[Test]
    /** @test */
    public function successfulGetHeroiconWithOnlyName(): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public');
        $twigExtension = new TwigHeroiconExtension($heroiconGetter);

        $svg = $twigExtension->getHeroicon('test');
        $this->assertStringContainsString(sprintf('viewBox="0 0 %s %s"', HeroiconSizes::TWENTY_FOUR->value, HeroiconSizes::TWENTY_FOUR->value), $svg);
        $this->assertStringContainsString('stroke-linecap="round"', $svg);  // default is outline
    }

    #[DataProvider(methodName: 'getTypeAndSizes')]
    #[Test]
    /**
     * @test
     * @dataProvider getTypeAndSizes
     */
    public function successfulGetHeroiconWithNameAndType(string $type, string $size): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public');
        $twigExtension = new TwigHeroiconExtension($heroiconGetter);

        $svg = $twigExtension->getHeroicon('test', $type);
        $this->assertStringContainsString(sprintf('viewBox="0 0 %s %s"', HeroiconSizes::TWENTY_FOUR->value, HeroiconSizes::TWENTY_FOUR->value), $svg);

        $typeStringSpecific = $type === 'outline' ? 'stroke-linecap="round"' : 'fill-rule="evenodd"';
        $this->assertStringContainsString($typeStringSpecific, $svg);
    }

    #[DataProvider(methodName: 'getTypeAndSizes')]
    #[Test]
    /**
     * @test
     * @dataProvider getTypeAndSizes
     */
    public function successfulGetHeroiconWithNameAndTypeAndSizes(string $type, string $size): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public');
        $twigExtension = new TwigHeroiconExtension($heroiconGetter);

        $svg = $twigExtension->getHeroicon('test', $type, $size);
        $this->assertStringContainsString(sprintf('viewBox="0 0 %s %s"', $size, $size), $svg);

        $typeStringSpecific = $type === 'outline' ? 'stroke-linecap="round"' : 'fill-rule="evenodd"';
        $this->assertStringContainsString($typeStringSpecific, $svg);
    }

    #[DataProvider(methodName: 'getTypeAndSizes')]
    #[Test]
    /**
     * @test
     * @dataProvider getTypeAndSizes
     */
    public function successfulGetHeroiconWithNameAndTypeAndSizesWithClassName(string $type, string $size): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public');
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
        yield 'Outline 20' => ['type' => 'outline', 'size' => '20'];
        yield 'Outline 16' => ['type' => 'outline', 'size' => '16'];
        yield 'Solid 24' => ['type' => 'solid', 'size' => '24'];
        yield 'Solid 20' => ['type' => 'solid', 'size' => '20'];
        yield 'Solid 16' => ['type' => 'solid', 'size' => '16'];
    }
}
