<?php

namespace Plugins\User\Providers;

use Aksara\Providers\AbstractModuleProvider;

class CapabilityServiceProvider extends AbstractModuleProvider
{
    protected $defer = true;

    public function provides()
    {
        return [
            \Plugins\User\RoleCapability\RoleCapabilityInterface::class,
            'rolecapability',
            \Plugins\User\RoleCapability\ConfigRepository::class,
            \Plugins\User\UserCapability\UserCapabilityInterface::class,
            'usercapability',
            \Plugins\User\UserCapability\CapabilityCache::class,
            'capabilitycache',
        ];
    }

    protected function safeRegister()
    {
        $this->app->bind(
            \Plugins\User\RoleCapability\RoleCapabilityInterface::class,
            \Plugins\User\RoleCapability\Interactor::class
        );

        $this->app->bind(
            'rolecapability',
            \Plugins\User\RoleCapability\RoleCapabilityInterface::class
        );

        $this->app->bind(
            \Plugins\User\RoleCapability\ConfigRepository::class,
            \Plugins\User\Repository\LaravelConfig::class
        );

        $this->app->singleton(
            \Plugins\User\UserCapability\UserCapabilityInterface::class,
            \Plugins\User\UserCapability\Interactor::class
        );

        $this->app->bind(
            'usercapability',
            \Plugins\User\UserCapability\UserCapabilityInterface::class
        );

        $this->app->bind(
            \Plugins\User\UserCapability\CapabilityCache::class,
            \Plugins\User\UserCapability\LaravelCapabilityCache::class
        );

        $this->app->bind(
            'capabilitycache',
            \Plugins\User\UserCapability\CapabilityCache::class
        );
    }
}

