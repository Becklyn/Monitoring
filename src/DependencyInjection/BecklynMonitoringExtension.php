<?php declare(strict_types=1);

namespace Becklyn\Monitoring\DependencyInjection;

use Becklyn\Monitoring\Config\MonitoringConfig;
use Becklyn\Monitoring\Sentry\CustomSanitizeDataProcessor;
use Becklyn\Monitoring\DependencyInjection\CompilerPass\ReleaseVersionPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class BecklynMonitoringExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @var ReleaseVersionPass
     */
    private $releaseVersionPass;


    public function __construct (ReleaseVersionPass $releaseVersionPass)
    {
        $this->releaseVersionPass = $releaseVersionPass;
    }


    /**
     * @inheritdoc
     */
    public function load (array $configs, ContainerBuilder $container)
    {
        // load services
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . "/../Resources/config")
        );
        $loader->load("services.yaml");

        // parse and pass config
        $config = $this->processConfiguration(new BecklynMonitoringConfiguration(), $configs);
        $container->getDefinition(MonitoringConfig::class)
            ->setArgument('$config', $config);

        // set release version here, as we need the project name
        $this->releaseVersionPass->setProjectName($config["project_name"]);
    }


    /**
     * @inheritDoc
     */
    public function prepend (ContainerBuilder $container)
    {
        // add sane defaults for the sentry configuration
        $container->prependExtensionConfig('sentry', [
            "options" => [
                "curl_method" => "async",
                "processors" => [
                    \Raven_Processor_SanitizeDataProcessor::class,
                    CustomSanitizeDataProcessor::class,
                ]
            ],
            "skip_capture" => [
                AccessDeniedHttpException::class,
                NotFoundHttpException::class,
            ],
        ]);
    }
}
