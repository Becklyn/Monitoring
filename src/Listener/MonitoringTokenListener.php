<?php declare(strict_types=1);

namespace Becklyn\Monitoring\Listener;

use Becklyn\Monitoring\Config\MonitoringConfig;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;


class MonitoringTokenListener implements EventSubscriberInterface
{
    /**
     * @var MonitoringConfig
     */
    private $config;


    /**
     * @param MonitoringConfig $config
     */
    public function __construct (MonitoringConfig $config)
    {
        $this->config = $config;
    }


    /**
     * @param FilterResponseEvent $event
     */
    public function onResponse (FilterResponseEvent $event) : void
    {
        // skip if not master request
        if (!$event->isMasterRequest())
        {
            return;
        }

        $response = $event->getResponse();

        // skip if not HTML response
        if (false === \strpos($response->headers->get("Content-Type"), "text/html"))
        {
            return;
        }

        $content = $response->getContent();

        if (false !== ($position = \strrpos($content, '</body>')))
        {
            $content = \substr($content, 0, $position)
                . $this->config->getUptimeMonitorHtmlString()
                . \substr($content, $position);

            $response->setContent($content);
        }
    }


    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents ()
    {
        return [
            KernelEvents::RESPONSE => "onResponse",
        ];
    }
}
