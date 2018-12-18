<?php declare(strict_types=1);

namespace Becklyn\Monitoring;

use Becklyn\AssetsBundle\Namespaces\RegisterAssetNamespacesCompilerPass;
use Becklyn\Monitoring\DependencyInjection\BecklynMonitoringExtension;
use Becklyn\Monitoring\DependencyInjection\CompilerPass\ReleaseVersionPass;
use Becklyn\Monitoring\Exception\AssetIntegrationFailedException;
use Symfony\Component\Asset\Packages;
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
        else if (!\class_exists(Packages::class))
        {
            throw new AssetIntegrationFailedException("No asset integration extension found. Please either install `becklyn/assets-bundle` or `symfony/asset` to use this bundle.");
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
