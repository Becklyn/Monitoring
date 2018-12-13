<?php declare(strict_types=1);

namespace Becklyn\Monitoring\Sentry;


class CustomSanitizeDataProcessor extends \Raven_Processor
{
    /**
     * @inheritDoc
     */
    public function process (&$data)
    {
        // remove the IP address from the data
        unset($data["user"]["ip_address"]);
    }
}
