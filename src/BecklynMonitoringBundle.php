<?php declare(strict_types=1);

namespace Becklyn\Monitoring;

use Becklyn\AssetsBundle\Namespaces\RegisterAssetNamespacesCompilerPass;
use Becklyn\Monitoring\DependencyInjection\BecklynMonitoringExtension;
use Becklyn\Monitoring\DependencyInjection\CompilerPass\ReleaseVersionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class BecklynMonitoringBundle extends Bundle
{
    /**
     * @var ReleaseVersionPass
     */
    private $releaseVersionPass;


    /**
     *
     */
    public function __construct ()
    {
        $this->releaseVersionPass = new ReleaseVersionPass();
    }

    /**
     * @inheritDoc
     */
    public function build (ContainerBuilder $container) : void
    {
        if (\class_exists(RegisterAssetNamespacesCompilerPass::class))
        {
            $container->addCompilerPass(
                new RegisterAssetNamespacesCompilerPass([
                    "monitoring" => __DIR__ . "/../src/Resources/public",
                ])
            );

            $container->addCompilerPass($this->releaseVersionPass);
        }
    }


    /**
     * @inheritDoc
     */
    public function getContainerExtension ()
    {
        return new BecklynMonitoringExtension($this->releaseVersionPass);
    }
}
