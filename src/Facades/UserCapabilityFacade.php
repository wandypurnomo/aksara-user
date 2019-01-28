<?php
namespace Plugins\User\Facades;

use Illuminate\Support\Facades\Facade;

class UserCapabilityFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'usercapability';
    }
}

