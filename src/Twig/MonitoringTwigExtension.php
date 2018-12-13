<?php declare(strict_types=1);

namespace Becklyn\Monitoring\Twig;


use Becklyn\AssetsBundle\Helper\AssetHelper;
use Becklyn\Hosting\Config\HostingConfig;
use Becklyn\Monitoring\Config\MonitoringConfig;


class MonitoringTwigExtension extends \Twig_Extension
{
    /**
     * @var MonitoringConfig
     */
    private $monitoringConfig;


    /**
     * @var HostingConfig
     */
    private $hostingConfig;


    /**
     * @var AssetHelper
     */
    private $assetHelper;


    /**
     * @var string
     */
    private $environment;


    /**
     * @var string
     */
    private $isDebug;


    /**
     * @param MonitoringConfig $monitoringConfig
     * @param HostingConfig    $hostingConfig
     * @param AssetHelper      $assetHelper
     * @param string           $environment
     * @param string           $isDebug
     */
    public function __construct (
        MonitoringConfig $monitoringConfig,
        HostingConfig $hostingConfig,
        AssetHelper $assetHelper,
        string $environment,
        string $isDebug
    )
    {
        $this->monitoringConfig = $monitoringConfig;
        $this->hostingConfig = $hostingConfig;
        $this->assetHelper = $assetHelper;
        $this->environment = $environment;
        $this->isDebug = $isDebug;
    }


    /**
     * @return string
     */
    public function embedMonitoring () : string
    {
        $trackJsToken = $this->monitoringConfig->getTrackJsToken();

        // only embed if token is set, in production and not in debug
        if (null === $trackJsToken || $this->isDebug || "prod" !== $this->environment)
        {
            return "";
        }

        return \sprintf(
            '<script src="%s"></script><script>window.TrackJS && TrackJS.install(%s)</script>',
            $this->assetHelper->getUrl("@monitoring/js/trackjs.js"),
            \json_encode([
                "token" => $trackJsToken,
                "application" => $this->hostingConfig->getDeploymentTier(),
                "version" => $this->hostingConfig->getGitCommit(),
                "console" => [
                    "display" => false,
                ],
            ])
        );
    }

    /**
     * @inheritdoc
     */
    public function getFunctions ()
    {
        return [
            new \Twig_Function("monitoring_embed", [$this, "embedMonitoring"], ["is_safe" => ["html"]]),
        ];
    }
}