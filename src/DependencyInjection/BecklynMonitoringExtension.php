<?php declare(strict_types=1);

namespace Becklyn\Monitoring\DependencyInjection;

use Becklyn\Hosting\Git\GitIntegration;
use Becklyn\Monitoring\Config\MonitoringConfig;
use Becklyn\Monitoring\Sentry\CustomSanitizeDataProcessor;
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
    }


    /**
     * @inheritDoc
     */
    public function prepend (ContainerBuilder $container)
    {
        // add sane defaults for the sentry configuration
        $git = new GitIntegration($container->getParameter('kernel.project_dir'));

        $container->prependExtensionConfig('sentry', [
            "options" => [
                "curl_method" => "async",
                "release" => $git->fetchHeadCommitHash(),
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
