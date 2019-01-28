<?php
namespace Plugins\User\Repository;

use Plugins\User\RoleCapability\ConfigRepository;
use Illuminate\Config\Repository as Config;

class LaravelConfig implements ConfigRepository
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get the specified configuration value.
     *
     * @param  array|string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     * Set a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $this->config->set($key, $value);
    }
}
