<?php declare(strict_types=1);

namespace Becklyn\Monitoring\Config;


class MonitoringConfig
{
    /**
     * @var array
     */
    private $config;


    /**
     * @param array $config
     */
    public function __construct (array $config)
    {
        $this->config = $config;
    }


    /**
     * Returns the track js tracking code
     *
     * @return string|null
     */
    public function getTrackJsToken () : ?string
    {
        return null !== $this->config["trackjs"]
            ? (string) $this->config["trackjs"]
            : null;
    }


    /**
     * @return string
     */
    public function getUptimeMonitorHtmlString () : string
    {
        $name = \htmlspecialchars($this->getProjectName(), \ENT_QUOTES);
        return "<!-- uptime monitor: $name -->";
    }


    /**
     * @return string
     */
    private function getProjectName () : string
    {
        return $this->config["project_name"];
    }
}
