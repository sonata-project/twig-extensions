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

use Symfony\Component\Config\Definition\BaseNode;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * @author Alexander <iam.asm89@gmail.com>
 */
class Configuration implements ConfigurationInterface
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
                            // NEXT_MAJOR: change types to string[]
                            ->arrayNode('types')
                                ->useAttributeAsKey('type')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('domain')
                                            ->defaultValue('SonataTwigBundle')
                                            ->setDeprecated(...$this->getDomainParamDeprecationMsg())
                                            ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    // BC layer for deprecation messages for symfony/config < 5.1
    private function getDomainParamDeprecationMsg(): array
    {
        $message = 'The child node "%node%" at path "%path%" is deprecated since sonata-project/twig-extensions 1.4 and will be removed in 2.0 version. Translate you message before add it to session flash.';

        if (method_exists(BaseNode::class, 'getDeprecation')) {
            return [
                'sonata-project/twig-extensions',
                '1.4',
                $message,
            ];
        }

        return [$message];
    }
}
