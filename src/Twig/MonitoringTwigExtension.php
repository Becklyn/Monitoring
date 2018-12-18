<?php declare(strict_types=1);

namespace Becklyn\Monitoring\Twig;

use Becklyn\AssetsBundle\Helper\AssetHelper;
use Becklyn\Hosting\Config\HostingConfig;
use Becklyn\Monitoring\Config\MonitoringConfig;
use Symfony\Component\Asset\Packages;

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
     * @var string
     */
    private $environment;

    /**
     * @var string
     */
    private $isDebug;

    /**
     * @var AssetHelper|null
     */
    private $assetHelper;

    /**
     * @var Packages|null
     */
    private $packages;


    /**
     * @param MonitoringConfig $monitoringConfig
     * @param HostingConfig    $hostingConfig
     * @param AssetHelper|null $assetHelper
     * @param Packages|null    $packages
     * @param string           $environment
     * @param string           $isDebug
     */
    public function __construct (
        MonitoringConfig $monitoringConfig,
        HostingConfig $hostingConfig,
        ?AssetHelper $assetHelper,
        ?Packages $packages,
        string $environment,
        string $isDebug
    )
    {
        $this->monitoringConfig = $monitoringConfig;
        $this->hostingConfig = $hostingConfig;
        $this->assetHelper = $assetHelper;
        $this->packages = $packages;
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
            $assetUrl = null !== $this->assetHelper
                ? $this->assetHelper->getUrl("@monitoring/js/trackjs.js")
                : $this->packages->getUrl("bundles/becklyn-monitoring/js/trackjs.js"),
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
    public function getFunctions () : iterable
    {
        return [
            new \Twig_Function("monitoring_embed", [$this, "embedMonitoring"], ["is_safe" => ["html"]]),
        ];
    }
}
