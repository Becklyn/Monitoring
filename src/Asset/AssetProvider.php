<?php declare(strict_types=1);

namespace Becklyn\Monitoring\Asset;

use Becklyn\AssetsBundle\Helper\AssetHelper;
use Symfony\Component\Asset\Packages;

class AssetProvider
{
    /**
     * @var AssetHelper|null
     */
    private $assetHelper;

    /**
     * @var Packages|null
     */
    private $packages;

    /**
     * @param AssetHelper|null $assetHelper
     * @param Packages|null    $packages
     */
    public function __construct (?AssetHelper $assetHelper, ?Packages $packages)
    {
        $this->assetHelper = $assetHelper;
        $this->packages = $packages;
    }

    /**
     * @param string $assetPath
     *
     * @return string
     * @throws \Becklyn\AssetsBundle\Exception\AssetsException
     */
    public function getUrl (string $assetPath) : string
    {
        return null !== $this->assetHelper
            ? $this->assetHelper->getUrl("@monitoring/{$assetPath}")
            : $this->packages->getUrl("bundles/becklyn-monitoring/{$assetPath}");
    }
}
