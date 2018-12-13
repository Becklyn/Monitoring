<?php declare(strict_types=1);

namespace Becklyn\Monitoring\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class BecklynMonitoringBundleConfiguration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder ()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root("becklyn_monitoring")
            ->children()
            ->end();

        return $treeBuilder;
    }
}
