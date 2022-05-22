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

use Sonata\Twig\Extension\FormTypeExtension;
use Sonata\Twig\Extension\StatusExtension;
use Sonata\Twig\Extension\StatusRuntime;
use Sonata\Twig\Extension\TemplateExtension;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->services()

        ->set('sonata.twig.extension.wrapping', FormTypeExtension::class)
            ->tag('twig.extension')
            ->args([param('sonata.twig.form_type')])

        ->set('sonata.twig.status_runtime', StatusRuntime::class)
            ->tag('twig.runtime')

        ->set('sonata.twig.status_extension', StatusExtension::class)
            ->tag('twig.extension')

        ->set('sonata.twig.template_extension', TemplateExtension::class)
            ->tag('twig.extension')
            ->args([param('kernel.debug')]);
};
