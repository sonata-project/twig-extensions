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

namespace Sonata\Twig\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * @author Alexander <iam.asm89@gmail.com>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sonata_twig');

        $rootNode = $treeBuilder->getRootNode();

        $this->addFlashMessageSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Returns configuration for flash messages.
     *
     * @psalm-suppress PossiblyNullReference, UndefinedInterfaceMethod
     *
     * @see https://github.com/psalm/psalm-plugin-symfony/issues/174
     */
    private function addFlashMessageSection(ArrayNodeDefinition $node): void
    {
        $validFormTypeValues = ['standard', 'horizontal'];

        $node
            ->children()
                ->enumNode('form_type')
                    ->defaultValue('standard')
                    ->values($validFormTypeValues)
                    ->info('Style used in the forms, some of the widgets need to be wrapped in a special div element
depending on this style.')
                ->end()
                ->arrayNode('flashmessage')
                    ->useAttributeAsKey('message')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('css_class')->end()
                            ->arrayNode('types')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
