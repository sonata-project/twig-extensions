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
use Sonata\Twig\Extension\FlashMessageRuntime;
use Sonata\Twig\FlashMessage\FlashManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

final class FlashMessageRuntimeTest extends TestCase
{
    public function testStatusClassDefaultValue(): void
    {
        $flashManager = $this->getFlashManager([
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

        $runtime = new FlashMessageRuntime($flashManager);

        static::assertSame('test-value', $runtime->getFlashMessagesClass('test', 'test-value'));
        static::assertSame('danger', $runtime->getFlashMessagesClass('error', 'test-value'));
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
        $request->setSession($this->getSession());

        return new FlashManager($requestStack, $types, $classes);
    }
}
