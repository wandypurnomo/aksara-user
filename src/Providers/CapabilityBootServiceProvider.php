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

        \Eventy::addAction('aksara.role.saving', function ($role) {

            $capabilityAfter = $role->permissions ?? [];

            $currentRole = \Plugins\User\Models\Role::find($role->id);
            $currentCapability = $currentRole->permissions ?? [];

            $removed = array_diff($currentCapability, $capabilityAfter);
            foreach ($removed as $removeCapability) {
                \CapabilityCache::flushCapability($removeCapability);
            }

            $added = array_diff($capabilityAfter, $currentCapability);
            foreach ($added as $addedCapability) {
                \CapabilityCache::flushCapability($addedCapability);
            }
        });

        \Eventy::addAction('aksara.role.deleting', function ($role) {
            $currentRole = \Plugins\User\Models\Role::find($role->id);
            $capabilities = $currentRole->permissions ?? [];
            foreach ($capabilities as $capability) {
                \CapabilityCache::flushCapability($capability);
            }
        });
    }
}
