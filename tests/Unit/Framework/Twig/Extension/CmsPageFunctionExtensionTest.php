<?php declare(strict_types=1);

namespace Jhae\TwigExtensions\Test\Unit\Framework\Twig\Extension;

use Jhae\TwigExtensions\Framework\Twig\Extension\CmsPageFunctionExtension;
use Jhae\TwigExtensions\Framework\Twig\Extension\CmsPageFunctionRuntime;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * Cms page function extension test class
 *
 * @covers \Jhae\TwigExtensions\Framework\Twig\Extension\CmsPageFunctionExtension
 */
class CmsPageFunctionExtensionTest extends TestCase
{
    /**
     * Test get functions
     */
    public function testGetFunctions(): void
    {
        $cmsPageFunctionExtension = $this->createPartialMock(CmsPageFunctionExtension::class, []);

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
            ],
            $cmsPageFunctionExtension->getFunctions(),
        );
    }
}
