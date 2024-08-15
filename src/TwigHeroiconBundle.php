<?php

namespace Yalit\TwigHeroiconBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Yalit\TwigHeroiconBundle\Twig\TwigHeroiconExtension;

class TwigHeroiconBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition
            ->rootNode()
            ->children()
            ->scalarNode('heroicon_getter')
            ->defaultValue('yalit.heroicon.getter.webpack')
            ->end()
            ->scalarNode('with_webpack')
            ->defaultTrue()
            ->end()
            ->scalarNode('default_display_type')
            ->defaultValue('outline')
            ->end()
            ->scalarNode('default_size')
            ->defaultValue('24')
            ->end()
            ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $heroiconGetter = $builder->getDefinition($config['heroicon_getter']);
        $container
            ->services()
            ->get(TwigHeroiconExtension::class)
            ->arg(0, $heroiconGetter)
            ->arg(1, $config['with_webpack'])
            ->arg(2, $config['default_display_type'])
            ->arg(3, $config['default_size']);
    }
}
