<?php declare(strict_types=1);

namespace Becklyn\Monitoring\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class BecklynMonitoringConfiguration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder ()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root("becklyn_monitoring")
            ->children()
                ->scalarNode("project_name")
                    ->isRequired()
                ->end()
                ->scalarNode("trackjs")
                    ->defaultNull()
                    ->info("The token for the TrackJS integration.")
                ->end()
            ->end();

        return $treeBuilder;
    }
}
