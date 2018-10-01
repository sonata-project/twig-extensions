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

namespace Sonata\Twig\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sonata\Twig\DependencyInjection\SonataTwigExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SonataTwigExtensionTest extends AbstractExtensionTestCase
{
    public function testAfterLoadingTheWrappingParameterIsSet(): void
    {
        $this->container->setParameter('kernel.bundles', []);
    }

    public function testHorizontalFormTypeMeansNoWrapping(): void
    {
        $this->container->setParameter('kernel.bundles', []);
    }

    public function testPrepend(): void
    {
        $containerBuilder = $this->prophesize(ContainerBuilder::class);

        $containerBuilder->getExtensionConfig('sonata_admin')->willReturn([
            ['some_key_we_do_not_care_about' => 42],
        ]);

        $containerBuilder->prependExtensionConfig(
            'sonata_twig', []
        )->shouldBeCalled();

        $containerBuilder->prependExtensionConfig(
            'sonata_twig', []
        )->shouldBeCalled();

        $extension = new SonataTwigExtension();
        $extension->prepend($containerBuilder->reveal());
    }

    protected function getContainerExtensions()
    {
        return [
            new SonataTwigExtension(),
        ];
    }
}
