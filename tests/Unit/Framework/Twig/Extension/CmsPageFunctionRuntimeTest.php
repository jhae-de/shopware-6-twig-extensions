<?php declare(strict_types=1);

namespace Jhae\TwigExtensions\Test\Unit\Framework\Twig\Extension;

use Jhae\TwigExtensions\Framework\Twig\Extension\CmsPageFunctionRuntime;
use MaSpeng\TestHelper\ObjectReflectorTrait;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageLoaderInterface;
use Shopware\Core\Framework\Adapter\Twig\Exception\StringTemplateRenderingException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\Error;

/**
 * Cms page function runtime test class
 *
 * @covers \Jhae\TwigExtensions\Framework\Twig\Extension\CmsPageFunctionRuntime
 */
class CmsPageFunctionRuntimeTest extends TestCase
{
    use ObjectReflectorTrait;
    use ProphecyTrait;

    /**
     * Test construct
     */
    public function testConstruct(): void
    {
        $cmsPageFunctionRuntime = new CmsPageFunctionRuntime(
            $this->createStub(Environment::class),
            $this->createStub(SalesChannelCmsPageLoaderInterface::class),
            $this->createStub(RequestStack::class),
        );

        static::assertNotNull($cmsPageFunctionRuntime);
    }

    /**
     * Test render cms page with invalid context
     */
    public function testRenderCmsPageWithInvalidContext(): void
    {
        $cmsPageFunctionRuntime = $this->createPartialMock(CmsPageFunctionRuntime::class, []);

        self::assertSame(
            '',
            $cmsPageFunctionRuntime->renderCmsPage(
                [
                    'context' => null,
                ],
                Uuid::randomHex(),
            ),
        );
    }

    /**
     * Test render cms page with invalid id
     */
    public function testRenderCmsPageWithInvalidId(): void
    {
        $cmsPageFunctionRuntime = $this->createPartialMock(CmsPageFunctionRuntime::class, []);

        self::assertSame(
            '',
            $cmsPageFunctionRuntime->renderCmsPage(
                [
                    'context' => $this->createStub(SalesChannelContext::class),
                ],
                null,
            ),
        );
    }

    /**
     * Test render cms page with missing cms page
     */
    public function testRenderCmsPageWithMissingCmsPage(): void
    {
        $salesChannelContext = $this->createStub(SalesChannelContext::class);
        $cmsPageId = Uuid::randomHex();
        $request = $this->createStub(Request::class);
        $criteria = $this->createStub(Criteria::class);

        $entitySearchResult = $this->prophesize(EntitySearchResult::class);
        $entitySearchResult->first()
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $cmsPageLoader = $this->prophesize(SalesChannelCmsPageLoaderInterface::class);
        $cmsPageLoader->load($request, $criteria, $salesChannelContext)
            ->shouldBeCalledOnce()
            ->willReturn($entitySearchResult->reveal());

        $requestStack = $this->prophesize(RequestStack::class);
        $requestStack->getCurrentRequest()
            ->shouldBeCalledOnce()
            ->willReturn($request);

        $cmsPageFunctionRuntime = $this->createPartialMock(CmsPageFunctionRuntime::class, ['getCriteria']);

        $cmsPageFunctionRuntime->expects(self::once())
            ->method('getCriteria')
            ->with($cmsPageId)
            ->willReturn($criteria);

        self::setPropertyValues($cmsPageFunctionRuntime, [
            'cmsPageLoader' => $cmsPageLoader->reveal(),
            'requestStack' => $requestStack->reveal(),
        ]);

        self::assertSame(
            '',
            $cmsPageFunctionRuntime->renderCmsPage(
                [
                    'context' => $salesChannelContext,
                ],
                $cmsPageId,
            ),
        );
    }

    /**
     * Test render cms page with existing cms page
     */
    public function testRenderCmsPageWithExistingCmsPage(): void
    {
        $salesChannelContext = $this->createStub(SalesChannelContext::class);
        $cmsPageId = Uuid::randomHex();
        $request = $this->createStub(Request::class);
        $criteria = $this->createStub(Criteria::class);
        $cmsPage = $this->createStub(CmsPageEntity::class);

        $entitySearchResult = $this->prophesize(EntitySearchResult::class);
        $entitySearchResult->first()
            ->shouldBeCalledOnce()
            ->willReturn($cmsPage);

        $cmsPageLoader = $this->prophesize(SalesChannelCmsPageLoaderInterface::class);
        $cmsPageLoader->load($request, $criteria, $salesChannelContext)
            ->shouldBeCalledOnce()
            ->willReturn($entitySearchResult->reveal());

        $requestStack = $this->prophesize(RequestStack::class);
        $requestStack->getCurrentRequest()
            ->shouldBeCalledOnce()
            ->willReturn($request);

        $twig = $this->prophesize(Environment::class);
        $twig->render('@Storefront/storefront/page/content/detail.html.twig', [
            'cmsPage' => $cmsPage,
        ])
            ->shouldBeCalledOnce()
            ->willReturn('_rendered_template_');

        $cmsPageFunctionRuntime = $this->createPartialMock(CmsPageFunctionRuntime::class, ['getCriteria']);

        $cmsPageFunctionRuntime->expects(self::once())
            ->method('getCriteria')
            ->with($cmsPageId)
            ->willReturn($criteria);

        self::setPropertyValues($cmsPageFunctionRuntime, [
            'twig' => $twig->reveal(),
            'cmsPageLoader' => $cmsPageLoader->reveal(),
            'requestStack' => $requestStack->reveal(),
        ]);

        self::assertSame(
            '_rendered_template_',
            $cmsPageFunctionRuntime->renderCmsPage(
                [
                    'context' => $salesChannelContext,
                ],
                $cmsPageId,
            ),
        );
    }

    /**
     * Test render cms page with existing cms page throws exception
     */
    public function testRenderCmsPageWithExistingCmsPageThrowsException(): void
    {
        $salesChannelContext = $this->createStub(SalesChannelContext::class);
        $cmsPageId = Uuid::randomHex();
        $request = $this->createStub(Request::class);
        $criteria = $this->createStub(Criteria::class);
        $cmsPage = $this->createStub(CmsPageEntity::class);

        $entitySearchResult = $this->prophesize(EntitySearchResult::class);
        $entitySearchResult->first()
            ->shouldBeCalledOnce()
            ->willReturn($cmsPage);

        $cmsPageLoader = $this->prophesize(SalesChannelCmsPageLoaderInterface::class);
        $cmsPageLoader->load($request, $criteria, $salesChannelContext)
            ->shouldBeCalledOnce()
            ->willReturn($entitySearchResult->reveal());

        $requestStack = $this->prophesize(RequestStack::class);
        $requestStack->getCurrentRequest()
            ->shouldBeCalledOnce()
            ->willReturn($request);

        $twigError = new Error('_twig_error_message_');

        $twig = $this->prophesize(Environment::class);
        $twig->render('@Storefront/storefront/page/content/detail.html.twig', [
            'cmsPage' => $cmsPage,
        ])
            ->shouldBeCalledOnce()
            ->willThrow($twigError);

        $cmsPageFunctionRuntime = $this->createPartialMock(CmsPageFunctionRuntime::class, ['getCriteria']);

        $cmsPageFunctionRuntime->expects(self::once())
            ->method('getCriteria')
            ->with($cmsPageId)
            ->willReturn($criteria);

        self::setPropertyValues($cmsPageFunctionRuntime, [
            'twig' => $twig->reveal(),
            'cmsPageLoader' => $cmsPageLoader->reveal(),
            'requestStack' => $requestStack->reveal(),
        ]);

        $this->expectException(StringTemplateRenderingException::class);
        $this->expectExceptionMessage('_twig_error_message_');

        self::assertSame(
            '_rendered_template_',
            $cmsPageFunctionRuntime->renderCmsPage(
                [
                    'context' => $salesChannelContext,
                ],
                $cmsPageId,
            ),
        );
    }

    /**
     * Test get criteria
     */
    public function testGetCriteria(): void
    {
        $cmsPageFunctionRuntime = $this->createPartialMock(CmsPageFunctionRuntime::class, []);

        self::assertInstanceOf(
            Criteria::class,
            self::invokeMethod($cmsPageFunctionRuntime, 'getCriteria', ['_cms_page_id_']),
        );
    }
}
