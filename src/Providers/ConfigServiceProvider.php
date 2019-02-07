<?php

namespace Plugins\User\Providers;

use Aksara\Providers\AbstractModuleProvider;

class ConfigServiceProvider extends AbstractModuleProvider
{
    protected function safeRegister()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/auth.php', 'auth');
    }

}
