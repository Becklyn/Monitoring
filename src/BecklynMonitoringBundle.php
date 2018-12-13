<?php declare(strict_types=1);

namespace Becklyn\Monitoring;

use Becklyn\AssetsBundle\Namespaces\RegisterAssetNamespacesCompilerPass;
use Becklyn\Monitoring\DependencyInjection\BecklynMonitoringExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class BecklynMonitoringBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build (ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new RegisterAssetNamespacesCompilerPass([
                "monitoring" => __DIR__ . "/../build",
            ])
        );
    }


    /**
     * @inheritDoc
     */
    public function getContainerExtension ()
    {
        return new BecklynMonitoringExtension();
    }
}
