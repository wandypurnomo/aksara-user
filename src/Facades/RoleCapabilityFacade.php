<?php
namespace Plugins\User\Facades;

use Illuminate\Support\Facades\Facade;

class RoleCapabilityFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rolecapability';
    }
}
