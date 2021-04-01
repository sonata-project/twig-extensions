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

use Sonata\Twig\Extension\DeprecatedTemplateExtension;
use Sonata\Twig\Extension\FormTypeExtension;
use Sonata\Twig\Extension\StatusExtension;
use Sonata\Twig\Extension\StatusRuntime;
use Sonata\Twig\Extension\TemplateExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // Use "service" function for creating references to services when dropping support for Symfony 4.4
    // Use "param" function for creating references to parameters when dropping support for Symfony 5.1
    $containerConfigurator->services()

        ->set('sonata.twig.extension.wrapping', FormTypeExtension::class)
            ->tag('twig.extension')
            ->args(['%sonata.twig.form_type%'])

        ->set('sonata.twig.status_runtime', StatusRuntime::class)
            ->tag('twig.runtime')

        ->set('sonata.twig.status_extension', StatusExtension::class)
            ->tag('twig.extension')

        // NEXT_MAJOR: Remove this service.
        ->set('sonata.twig.deprecated_template_extension', DeprecatedTemplateExtension::class)
            ->tag('twig.extension')

        ->set('sonata.twig.template_extension', TemplateExtension::class)
            ->tag('twig.extension')
            ->args([
                '%kernel.debug%',
                new ReferenceConfigurator('sonata.doctrine.model.adapter.chain'),
            ]);
};
