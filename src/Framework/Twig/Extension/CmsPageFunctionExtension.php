<?php declare(strict_types=1);

namespace Jhae\TwigExtensions\Framework\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Cms page function extension class
 */
class CmsPageFunctionExtension extends AbstractExtension
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
                ]
            ),
        ];
    }
}
