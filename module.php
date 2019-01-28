<?php

return [
    'name' => 'user',
    'description' => 'User and Role manager for Aksara',

    //Laravel service Providers defined in plugin
    'providers' => [
        'Plugins\\User\\Providers\\UserServiceProvider',
        'Plugins\\User\\Providers\\RoleServiceProvider',
        'Plugins\\User\\Providers\\CapabilityServiceProvider',
        'Plugins\\User\\Providers\\CapabilityBootServiceProvider',
    ],

    //Laravel Facade aliases defined in plugin
    'aliases' => [
        'RoleCapability' => 'Plugins\\User\\Facades\\RoleCapabilityFacade',
        'UserCapability' => 'Plugins\\User\\Facades\\UserCapabilityFacade',
        'CapabilityCache' => 'Plugins\\User\\Facades\\CapabilityCacheFacade',
    ],

    'type' => 'plugin',

    //flag this module as master
    //default master if not specified
    'context' => 'master',
];
