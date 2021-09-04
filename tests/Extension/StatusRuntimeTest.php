<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Twig\Tests\Extension;

use PHPUnit\Framework\TestCase;
use Sonata\Twig\Extension\StatusRuntime;
use Sonata\Twig\FlashMessage\FlashManager;
use Sonata\Twig\Status\StatusClassRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class StatusRuntimeTest extends TestCase
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var FlashManager
     */
    protected $flashManager;

    /**
     * Set up units tests.
     */
    protected function setUp(): void
    {
        $this->session = $this->getSession();
        $this->flashManager = $this->getFlashManager([
            'success' => [
                'my_bundle_success' => [],
                'my_second_bundle_success' => [],
            ],
            'warning' => [
                'my_bundle_warning' => [],
                'my_second_bundle_warning' => [],
            ],
            'error' => [
                'my_bundle_error' => [],
                'my_second_bundle_error' => [],
            ],
        ]);
    }

    public function testStatusClassDefaultValue(): void
    {
        $runtime = new StatusRuntime();
        $statusService = $this->createMock(StatusClassRendererInterface::class);

        $statusService->expects(static::once())
            ->method('handlesObject')
            ->willReturn(false);

        $runtime->addStatusService($statusService);
        // getStatusClass() for StatusClassRenderer
        static::assertSame('test-value', $runtime->statusClass(new \stdClass(), 'getStatus', 'test-value'));

        // getStatusClass() for FlashManager
        static::assertSame('test-value', $runtime->statusClass('getStatus', null, 'test-value'));
    }

    /**
     * NEXT_MAJOR: remove this method.
     */
    public function testFlashManagerInSonataStatusRenderer(): void
    {
        // Given
        $this->flashManager->addFlash('my_bundle_success', 'hey, success dude!');
        $this->session->getFlashBag()->set('my_second_bundle_success', 'hey, success dude!');

        // When
        $this->flashManager->get('success');

        // Then
        $statusRuntime = new StatusRuntime();
        $statusRuntime->addStatusService($this->flashManager);

        static::assertSame(
            'danger',
            $statusRuntime->statusClass($this->flashManager, 'error', 'default_class')
        );

        static::assertSame(
            'default_class',
            $statusRuntime->statusClass($this->flashManager, 'blabla', 'default_class')
        );
    }

    /**
     * Returns a Symfony session service.
     */
    protected function getSession(): Session
    {
        return new Session(new MockArraySessionStorage(), new AttributeBag(), new FlashBag());
    }

    /**
     * Returns Sonata flash manager.
     */
    protected function getFlashManager(array $types): FlashManager
    {
        $classes = ['error' => 'danger'];
        $requestStack = new RequestStack();
        $requestStack->push($request = new Request());
        $request->setSession($this->session);

        return new FlashManager($requestStack, $types, $classes);
    }
}
