<?php declare(strict_types=1);

namespace Jhae\TwigExtensions\Framework\Twig;

use Jhae\TwigExtensions\Framework\Twig\Extension\CmsPageFunctionRuntime;
use Jhae\TwigExtensions\Framework\Twig\Extension\ColorBrightnessFunctionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Extension class
 */
class Extension extends AbstractExtension
{
    /**
     * Get functions
     *
     * @return array<int, TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
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
                ],
            ),
            new TwigFunction(
                'hex_color_brightness',
                [
                    ColorBrightnessFunctionRuntime::class,
                    'adjustHexColorBrightness',
                ],
            ),
        ];
    }
}
