<?php

namespace Sonata\TwigExtensions\Bridge\Symfony\Bundle;

use Sonata\TwigExtensions\Bridge\Symfony\DependencyInjection\Compiler\StatusRendererCompilerPass;
use Sonata\TwigExtensions\Bridge\Symfony\DependencyInjection\SonataTwigExtensionsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SonataTwigExtensionsBundle extends Bundle
{
    public function getPath()
    {
        return __DIR__.'/..';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new StatusRendererCompilerPass());
    }

    protected function getContainerExtensionClass()
    {
        return SonataTwigExtensionsExtension::class;
    }
}
