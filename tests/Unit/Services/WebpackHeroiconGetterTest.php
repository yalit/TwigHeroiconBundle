<?php declare(strict_types=1);

namespace Yalit\TwigHeroiconBundle\Tests\Unit\Services;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Yalit\TwigHeroiconBundle\Services\WebpackHeroiconGetter;

class WebpackHeroiconGetterTest extends TestCase
{
    #[DataProvider(methodName: 'getTypeAndSizes')]
    #[Test]
    /**
     * @test
     * @dataProvider getTypeAndSizes
     */
    public function successfulGetHeroiconWithNoClassName(string $type, string $size): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public');

        $svg = $heroiconGetter->getHeroicon('test', $type, $size, '');
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
    public function successfulGetHeroiconWithClassName(string $type, string $size): void
    {
        $heroiconGetter = new WebpackHeroiconGetter(getcwd() . '/tests/public');

        $className = 'icon h-4 w-4 rounded stroke-blue-800';

        $svg = $heroiconGetter->getHeroicon('test', $type, $size, $className);
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
        yield 'Outline 20' => ['type' => 'outline', 'size' => '20'];
        yield 'Outline 16' => ['type' => 'outline', 'size' => '16'];
        yield 'Solid 24' => ['type' => 'solid', 'size' => '24'];
        yield 'Solid 20' => ['type' => 'solid', 'size' => '20'];
        yield 'Solid 16' => ['type' => 'solid', 'size' => '16'];
    }
}
