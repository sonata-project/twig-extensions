<?php

namespace Sonata\TwigExtensions\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sonata_core');

        $this->addFlashMessageSection($rootNode);

        return $treeBuilder;
    }

    private function addFlashMessageSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('form_type')
                    ->defaultValue('standard')
                    ->validate()
                    ->ifNotInArray($validFormTypes = ['standard', 'horizontal'])
                        ->thenInvalid(sprintf(
                            'The form_type option value must be one of %s',
                            $validFormTypesString = implode(', ', $validFormTypes)
                        ))
                    ->end()
                    ->info(sprintf('Must be one of %s', $validFormTypesString))
                ->end()
                ->arrayNode('flashmessage')
                    ->useAttributeAsKey('message')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('css_class')->end()
                            ->arrayNode('types')
                                ->useAttributeAsKey('type')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('domain')->defaultValue('SonataCoreBundle')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
