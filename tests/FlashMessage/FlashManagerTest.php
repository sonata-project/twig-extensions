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

namespace Sonata\Twig\Tests\FlashMessage;

use PHPUnit\Framework\TestCase;
use Sonata\Twig\FlashMessage\FlashManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * @author Vincent Composieux <composieux@ekino.com>
 */
final class FlashManagerTest extends TestCase
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var FlashManager
     */
    protected $flashManager;

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

    /**
     * Test the flash manager getSession() method.
     */
    public function testGetSession(): void
    {
        // When
        $session = $this->flashManager->getSession();

        // Then
        static::assertInstanceOf(Session::class, $session);
    }

    /**
     * NEXT_MAJOR: Remove this test.
     *
     * @psalm-suppress DeprecatedMethod
     *
     * @group legacy
     */
    public function testGetHandledObject(): void
    {
        static::assertTrue($this->flashManager->handlesObject($this->flashManager, 'error'));
        static::assertFalse($this->flashManager->handlesObject($this->flashManager, 'warning'));
    }

    public function testGetHandledTypes(): void
    {
        static::assertSame(['success', 'warning', 'error'], $this->flashManager->getHandledTypes());

        static::assertTrue($this->flashManager->handlesType('error'));
        static::assertFalse($this->flashManager->handlesType('warning'));
    }

    /**
     * NEXT_MAJOR: Remove this test.
     *
     * @psalm-suppress DeprecatedMethod
     *
     * @group legacy
     */
    public function testGetStatusClass(): void
    {
        static::assertSame('danger', $this->flashManager->getStatusClass($this->flashManager, 'error'));

        static::assertSame('example', $this->flashManager->getStatusClass($this->flashManager, 'non_existing_status', 'example'));
    }

    public function testGetStatus(): void
    {
        static::assertSame('danger', $this->flashManager->getRenderedHtmlClassAttribute('error'));

        static::assertSame('example', $this->flashManager->getRenderedHtmlClassAttribute('non_existing_status', 'example'));
    }

    /**
     * Test the flash manager getTypes() method.
     */
    public function testGetTypes(): void
    {
        // When
        $types = $this->flashManager->getTypes();

        // Then
        static::assertCount(3, $types);
        static::assertSame([
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
        ], $types);
    }

    /**
     * Test the flash manager handle() method with registered types.
     */
    public function testHandlingRegisteredTypes(): void
    {
        // Given
        $this->flashManager->addFlash('my_bundle_success', 'hey, success dude!');
        $this->session->getFlashBag()->set('my_second_bundle_success', 'hey, success dude!');

        $this->flashManager->addFlash('my_bundle_warning', 'hey, warning dude!');
        $this->session->getFlashBag()->set('my_second_bundle_warning', 'hey, warning dude!');

        $this->flashManager->addFlash('my_bundle_error', 'hey, error dude!');
        $this->session->getFlashBag()->set('my_second_bundle_error', 'hey, error dude!');

        // When
        $successMessages = $this->flashManager->get('success');
        $warningMessages = $this->flashManager->get('warning');
        $errorMessages = $this->flashManager->get('error');

        // Then
        static::assertCount(2, $successMessages);

        foreach ($successMessages as $message) {
            static::assertSame($message, 'hey, success dude!');
        }

        static::assertCount(2, $warningMessages);

        foreach ($warningMessages as $message) {
            static::assertSame($message, 'hey, warning dude!');
        }

        static::assertCount(2, $errorMessages);

        foreach ($errorMessages as $message) {
            static::assertSame($message, 'hey, error dude!');
        }
    }

    /**
     * Test the flash manager handle() method with non-registered types.
     */
    public function testHandlingNonRegisteredTypes(): void
    {
        // Given
        $this->session->getFlashBag()->set('non_registered_success', 'hey, success dude!');

        // When
        $messages = $this->flashManager->get('success');
        $nonRegisteredMessages = $this->flashManager->get('non_registered_success');

        // Then
        static::assertCount(0, $messages);

        static::assertCount(1, $nonRegisteredMessages);

        foreach ($nonRegisteredMessages as $message) {
            static::assertSame($message, 'hey, success dude!');
        }
    }

    /**
     * Test the flash manager get() method with a specified domain.
     */
    public function testFlashMessageWithCustomDomain(): void
    {
        // When
        $this->session->getFlashBag()->set('my_bundle_success', 'my_bundle_success_message');
        $messages = $this->flashManager->get('success');

        $this->session->getFlashBag()->set('my_bundle_success', 'my_bundle_success_message');
        $messagesWithoutDomain = $this->flashManager->get('success');

        // Then
        static::assertCount(1, $messages);
        static::assertCount(1, $messagesWithoutDomain);

        foreach ($messages as $message) {
            static::assertSame($message, 'my_bundle_success_message');
        }

        foreach ($messagesWithoutDomain as $message) {
            static::assertSame($message, 'my_bundle_success_message');
        }
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
     *
     * @param array<string, array<string, array<string, mixed>>> $types
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
