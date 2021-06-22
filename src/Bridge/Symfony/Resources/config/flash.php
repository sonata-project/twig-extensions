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

use Sonata\Twig\Extension\FlashMessageExtension;
use Sonata\Twig\Extension\FlashMessageRuntime;
use Sonata\Twig\FlashMessage\FlashManager;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()

        ->set('sonata.twig.flashmessage.manager.class', FlashManager::class)

        ->set('sonata.twig.extension.flashmessage.class', FlashMessageExtension::class);

    // Use "service" function for creating references to services when dropping support for Symfony 4.4
    // Use "param" function for creating references to parameters when dropping support for Symfony 5.1
    $containerConfigurator->services()

        ->set('sonata.twig.flashmessage.manager', '%sonata.twig.flashmessage.manager.class%')
            ->public()
            ->tag('sonata.status.renderer')
            ->args([
                new ReferenceConfigurator('request_stack'),
                [],
                [],
            ])

        ->set('sonata.twig.flashmessage.twig.runtime', FlashMessageRuntime::class)
            ->tag('twig.runtime')
            ->args([
                new ReferenceConfigurator('sonata.twig.flashmessage.manager'),
            ])

        ->set('sonata.twig.flashmessage.twig.extension', '%sonata.twig.extension.flashmessage.class%')
            ->public()
            ->tag('twig.extension');
};
