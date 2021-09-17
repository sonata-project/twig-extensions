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

namespace Sonata\Twig\Tests\Bridge\Symfony;

use PHPUnit\Framework\TestCase;
use Sonata\Twig\Bridge\Symfony\DependencyInjection\Compiler\StatusRendererCompilerPass;
use Sonata\Twig\Bridge\Symfony\SonataTwigBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Ahmet Akbana <ahmetakbana@gmail.com>
 */
final class SonataTwigBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder->expects(static::once())
            ->method('addCompilerPass')
            ->with(static::isInstanceOf(StatusRendererCompilerPass::class));

        $bundle = new SonataTwigBundle();
        $bundle->build($containerBuilder);
    }
}
