<?php
namespace Plugins\User\Facades;

use Illuminate\Support\Facades\Facade;

class CapabilityCacheFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'capabilitycache';
    }
}


