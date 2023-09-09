<?php declare(strict_types=1);

namespace Jhae\TwigExtensions\Test\Unit\Framework\Twig\Extension;

use InvalidArgumentException;
use Iterator;
use Jhae\TwigExtensions\Framework\Twig\Extension\ColorBrightnessFunctionRuntime;
use MaSpeng\TestHelper\ObjectReflectorTrait;
use PHPUnit\Framework\TestCase;

/**
 * Color brightness function runtime test class
 *
 * @covers \Jhae\TwigExtensions\Framework\Twig\Extension\ColorBrightnessFunctionRuntime
 */
class ColorBrightnessFunctionRuntimeTest extends TestCase
{
    use ObjectReflectorTrait;

    /**
     * Test adjust hex color brightness
     *
     * @dataProvider provideDataForTestAdjustHexColorBrightness
     */
    public function testAdjustHexColorBrightness(string $color, int $percent, string $expected): void
    {
        $functionRuntime = $this->createPartialMock(ColorBrightnessFunctionRuntime::class, [
            'validateHexColor',
            'validatePercent',
        ]);

        $functionRuntime->expects(self::once())
            ->method('validateHexColor')
            ->with($color);

        $functionRuntime->expects(self::once())
            ->method('validatePercent')
            ->with($percent);

        self::assertSame($expected, $functionRuntime->adjustHexColorBrightness($color, $percent));
    }

    /**
     * Provide data for test adjust hex color brightness
     */
    public function provideDataForTestAdjustHexColorBrightness(): Iterator
    {
        yield '3-digit color value with hash symbol and -100%' => [
            'color' => '#888',
            'percent' => -100,
            'expected' => '#000000',
        ];

        yield '3-digit color value with hash symbol and -25%' => [
            'color' => '#888',
            'percent' => -25,
            'expected' => '#666666',
        ];

        yield '3-digit color value without hash symbol and 0%' => [
            'color' => '888',
            'percent' => 0,
            'expected' => '888888',
        ];

        yield '6-digit color value with hash symbol and 25%' => [
            'color' => '#808080',
            'percent' => 25,
            'expected' => '#A0A0A0',
        ];

        yield '6-digit color value with hash symbol and 50%' => [
            'color' => '#808080',
            'percent' => 50,
            'expected' => '#C0C0C0',
        ];

        yield '6-digit color value without hash symbol and 100%' => [
            'color' => '808080',
            'percent' => 100,
            'expected' => 'FFFFFF',
        ];
    }

    /**
     * Test validate hex color should return
     *
     * @dataProvider provideDataForTestValidateHexColorShouldReturn
     */
    public function testValidateHexColorShouldReturn(string $color): void
    {
        $functionRuntime = $this->createPartialMock(ColorBrightnessFunctionRuntime::class, []);

        self::assertNull(
            self::invokeMethod($functionRuntime, 'validateHexColor', [
                $color,
            ]),
        );
    }

    /**
     * Provide data for test validate hex color should return
     */
    public function provideDataForTestValidateHexColorShouldReturn(): Iterator
    {
        yield '3-digit color value with hash symbol' => [
            'color' => '#888',
        ];

        yield '3-digit color value without hash symbol' => [
            'color' => '888',
        ];

        yield '6-digit color value with hash symbol' => [
            'color' => '#808080',
        ];

        yield '6-digit color value without hash symbol' => [
            'color' => '808080',
        ];
    }

    /**
     * Test validate hex color should throw an exception
     */
    public function testValidateHexColorShouldThrowAnException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The color must be a 3-digit or 6-digit hexadecimal value.');

        $functionRuntime = $this->createPartialMock(ColorBrightnessFunctionRuntime::class, []);

        self::invokeMethod($functionRuntime, 'validateHexColor', [
            '_color_',
        ]);
    }

    /**
     * Test validate percent should return
     *
     * @dataProvider provideDataForTestValidatePercentShouldReturn
     */
    public function testValidatePercentShouldReturn(int $percent): void
    {
        $functionRuntime = $this->createPartialMock(ColorBrightnessFunctionRuntime::class, []);

        self::assertNull(
            self::invokeMethod($functionRuntime, 'validatePercent', [
                $percent,
            ]),
        );
    }

    /**
     * Provide data for test validate percent should return
     */
    public function provideDataForTestValidatePercentShouldReturn(): Iterator
    {
        yield 'Percent of -100' => [
            'percent' => -100,
        ];

        yield 'Percent of 0' => [
            'percent' => 0,
        ];

        yield 'Percent of 100' => [
            'percent' => 100,
        ];
    }

    /**
     * Test validate percent should throw an exception
     *
     * @dataProvider provideDataForTestValidatePercentShouldThrowAnException
     */
    public function testValidatePercentShouldThrowAnException(int $percent): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The percent value must be an integer between -100 and 100.');

        $functionRuntime = $this->createPartialMock(ColorBrightnessFunctionRuntime::class, []);

        self::invokeMethod($functionRuntime, 'validatePercent', [
            $percent,
        ]);
    }

    /**
     * Provide data for test validate percent should throw an exception
     */
    public function provideDataForTestValidatePercentShouldThrowAnException(): Iterator
    {
        yield 'Percent less than -100' => [
            'percent' => -101,
        ];

        yield 'Percent greater than 100' => [
            'percent' => 101,
        ];
    }
}
