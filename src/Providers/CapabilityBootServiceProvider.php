<?php

namespace Plugins\User\Providers;

use Aksara\Providers\AbstractModuleProvider;

class CapabilityBootServiceProvider extends AbstractModuleProvider
{
    /**
     * Boot application services
     *
     * e.g, route, anything needs to be preload
     */
    protected function safeBoot()
    {
        \Eventy::addFilter('aksara.menu.admin-sub-menu', function ($adminSubMenu) {
            foreach ($adminSubMenu as $name => $subMenu) {
                foreach ($subMenu as $index => $item) {
                    $capable = \UserCapability::hasAny(@$item['capability'] ?? []);
                    if (!$capable) {
                        unset($adminSubMenu[$name][$index]);
                    }
                }
            }
            return $adminSubMenu;
        });

        \Eventy::addFilter('aksara.menu.admin-menu', function ($adminMenu) {
            foreach ($adminMenu as $name => $subMenu) {
                foreach ($subMenu as $index => $item) {
                    $capable = \UserCapability::hasAny(@$item['capability'] ?? []);
                    if (!$capable) {
                        unset($adminMenu[$name][$index]);
                    }
                }
            }
            return $adminMenu;
        });

        $this->registerHooks();
    }

    private function registerHooks()
    {
        \Eventy::addAction('aksara.user.role.removed', function ($userId) {
            \CapabilityCache::flushUser($userId);
        });

        \Eventy::addAction('aksara.user.role.added', function ($userId) {
            \CapabilityCache::flushUser($userId);
        });

        \Eventy::addAction('aksara.role.permission.saved', function ($permission) {
            \CapabilityCache::flushCapability($permission->permission);
        });

        \Eventy::addAction('aksara.role.permission.deleted', function ($permission) {
            \CapabilityCache::flushCapability($permission->permission);
        });
    }
}
