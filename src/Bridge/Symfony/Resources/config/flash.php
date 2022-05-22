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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sonata\Twig\Extension\FlashMessageExtension;
use Sonata\Twig\Extension\FlashMessageRuntime;
use Sonata\Twig\FlashMessage\FlashManager;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()

        ->set('sonata.twig.flashmessage.manager.class', FlashManager::class)

        ->set('sonata.twig.extension.flashmessage.class', FlashMessageExtension::class);

    $containerConfigurator->services()

        ->set('sonata.twig.flashmessage.manager', '%sonata.twig.flashmessage.manager.class%')
            ->public()
            ->tag('sonata.status.renderer')
            ->args([
                service('request_stack'),
                abstract_arg('Sonata flash message types array (defined in configuration)'),
                abstract_arg('Css classes associated with the types'),
            ])

        ->set('sonata.twig.flashmessage.twig.runtime', FlashMessageRuntime::class)
            ->tag('twig.runtime')
            ->args([
                service('sonata.twig.flashmessage.manager'),
            ])

        ->set('sonata.twig.flashmessage.twig.extension', '%sonata.twig.extension.flashmessage.class%')
            ->public()
            ->tag('twig.extension');
};
