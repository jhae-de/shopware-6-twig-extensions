<?php declare(strict_types=1);

namespace Jhae\TwigExtensions\Test\Unit\Framework\Twig;

use Jhae\TwigExtensions\Framework\Twig\Extension;
use Jhae\TwigExtensions\Framework\Twig\Extension\CmsPageFunctionRuntime;
use Jhae\TwigExtensions\Framework\Twig\Extension\ColorBrightnessFunctionRuntime;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * Extension test class
 *
 * @covers \Jhae\TwigExtensions\Framework\Twig\Extension
 */
class ExtensionTest extends TestCase
{
    /**
     * Test get functions
     */
    public function testGetFunctions(): void
    {
        $extension = $this->createPartialMock(Extension::class, []);

        self::assertEquals(
            [
                new TwigFunction(
                    'sw_cms_page',
                    [
                        CmsPageFunctionRuntime::class,
                        'renderCmsPage',
                    ],
                    [
                        'needs_context' => true,
                        'is_safe' => [
                            'html',
                        ],
                    ]
                ),
                new TwigFunction(
                    'hex_color_brightness',
                    [
                        ColorBrightnessFunctionRuntime::class,
                        'adjustHexColorBrightness',
                    ],
                ),
            ],
            $extension->getFunctions(),
        );
    }
}
