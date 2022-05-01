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
use Sonata\Twig\Status\StatusClassRendererInterface;

final class StatusRuntimeTest extends TestCase
{
    public function testStatusClassDefaultValue(): void
    {
        $runtime = new StatusRuntime();
        $statusService = $this->createMock(StatusClassRendererInterface::class);

        $statusService->expects(static::once())
            ->method('handlesObject')
            ->willReturn(false);

        $runtime->addStatusService($statusService);

        static::assertSame('test-value', $runtime->statusClass(new \stdClass(), 'getStatus', 'test-value'));
    }
}
