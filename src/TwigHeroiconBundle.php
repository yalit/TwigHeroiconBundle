<?php

namespace Yalit\TwigHeroiconBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Yalit\TwigHeroiconBundle\Services\NodeHeroiconGetter;
use Yalit\TwigHeroiconBundle\Services\WebpackHeroiconGetter;
use Yalit\TwigHeroiconBundle\Twig\TwigHeroiconExtension;

class TwigHeroiconBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition
            ->rootNode()
                ->children()
                    ->scalarNode('default_display_type')->defaultValue('outline')->end()
                    ->scalarNode('default_size')->defaultValue('24')->end()
                    ->arrayNode('webpack')
                        ->canBeEnabled()
                        ->children()
                            ->scalarNode('build_dir')->defaultValue('public/build')->end()
                        ->end()
                ->end()
            ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // set the getters
        $container->services()->set('yalit.heroicon.getter.webpack', WebpackHeroiconGetter::class)->arg(0, $builder->getParameter('kernel.project_dir') . '/' . $config['webpack']['build_dir']);
        $container->services()->set('yalit.heroicon.getter.node', NodeHeroiconGetter::class)->arg(0, $builder->getParameter('kernel.project_dir'));

        // define which getter to use
        $heroiconGetterId = match ($config['webpack']['enabled']) {
            true => 'yalit.heroicon.getter.webpack',
            false => 'yalit.heroicon.getter.node',
        };
        $heroiconGetter = $builder->getDefinition($heroiconGetterId);

        // set the extension
        $container
            ->services()
            ->set('yalit.heroicon.twig.extension', TwigHeroiconExtension::class)
            ->arg(0, $heroiconGetter)
            ->arg(1, $config['default_display_type'])
            ->arg(2, $config['default_size'])
            ->tag('twig.extension');
    }
}
