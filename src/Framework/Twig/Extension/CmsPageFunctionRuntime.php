<?php declare(strict_types=1);

namespace Jhae\TwigExtensions\Framework\Twig\Extension;

use Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageLoaderInterface;
use Shopware\Core\Framework\Adapter\Twig\Exception\StringTemplateRenderingException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\Error;

/**
 * Cms page function runtime class
 */
class CmsPageFunctionRuntime
{
    /**
     * Twig
     */
    private Environment $twig;

    /**
     * Cms page loader
     */
    private SalesChannelCmsPageLoaderInterface $cmsPageLoader;

    /**
     * Request stack
     */
    private RequestStack $requestStack;

    /**
     * Cms page function runtime constructor
     */
    public function __construct(
        Environment $twig,
        SalesChannelCmsPageLoaderInterface $cmsPageLoader,
        RequestStack $requestStack
    ) {
        $this->twig = $twig;
        $this->cmsPageLoader = $cmsPageLoader;
        $this->requestStack = $requestStack;
    }

    /**
     * Render cms page
     *
     * @param array<string, mixed> $context
     */
    public function renderCmsPage(array $context, ?string $id): string
    {
        $salesChannelContext = $context['context'];
        $cmsPageId = trim($id ?? '');

        if (!($salesChannelContext instanceof SalesChannelContext && Uuid::isValid($cmsPageId))) {
            return '';
        }

        $cmsPage = $this->cmsPageLoader
            ->load($this->requestStack->getCurrentRequest(), $this->getCriteria($cmsPageId), $salesChannelContext)
            ->first();

        try {
            return $cmsPage !== null
                ? $this->twig->render('@Storefront/storefront/page/content/detail.html.twig', [
                    'cmsPage' => $cmsPage,
                ])
                : '';
        }
        catch (Error $error) {
            throw new StringTemplateRenderingException($error->getMessage());
        }
    }

    /**
     * Get criteria
     */
    protected function getCriteria(string $cmsPageId): Criteria
    {
        return new Criteria([$cmsPageId]);
    }
}
