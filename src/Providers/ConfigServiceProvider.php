<?php

namespace Plugins\User\Providers;

use Aksara\Providers\AbstractModuleProvider;

class ConfigServiceProvider extends AbstractModuleProvider
{
    protected function safeRegister()
    {
        $this->overrideConfigFrom(__DIR__.'/../../config/auth.php', 'auth');
    }
}
