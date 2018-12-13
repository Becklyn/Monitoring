<?php declare(strict_types=1);

namespace Becklyn\Monitoring;

use Becklyn\Monitoring\DependencyInjection\BecklynMonitoringBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class BecklynMonitoringBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getContainerExtension ()
    {
        return new BecklynMonitoringBundleExtension();
    }
}
