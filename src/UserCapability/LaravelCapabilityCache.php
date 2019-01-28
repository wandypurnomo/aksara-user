<?php

namespace Plugins\User\UserCapability;

use Illuminate\Support\Str;

class LaravelCapabilityCache implements CapabilityCache
{
    const CACHE_KEY = 'aksara.capability';

    private function getCache()
    {
        if (\Cache::has(self::CACHE_KEY)) {
            return \Cache::get(self::CACHE_KEY);
        }
        return [];
    }

    private function getCapabilityKey($capabilityId, $args = [])
    {
        $argStr = '';
        if ($args) {
            $argStr = '.'.implode('.', $args);
        }
        return $capabilityId.$argStr;
    }

    public function put($userId, $capabilityId, $args, $capable)
    {
        $cache = $this->getCache();
        $capabilityKey = $this->getCapabilityKey($capabilityId, $args);
        $cache[$userId][$capabilityKey] = $capable;
        \Cache::put(self::CACHE_KEY, $cache, 1440);
    }

    public function has($userId, $capabilityId, $args = [])
    {
        $cache = $this->getCache();
        $capabilityKey = $this->getCapabilityKey($capabilityId, $args);
        return isset($cache[$userId][$capabilityKey]);
    }

    public function get($userId, $capabilityId, $args = [])
    {
        $cache = $this->getCache();
        $capabilityKey = $this->getCapabilityKey($capabilityId, $args);
        return $cache[$userId][$capabilityKey];
    }

    public function flushUser($userIdList)
    {
        $cache = $this->getCache();
        if (!is_array($userIdList)) {
            $userIdList = [ $userIdList ];
        }
        $users = implode(',', $userIdList);
        foreach ($userIdList as $userId) {
            unset($cache[$userId]);
        }
        \Cache::put(self::CACHE_KEY, $cache, 1440);
    }

    public function flushCapability($capabilityId)
    {
        $cache = $this->getCache();
        $context = $this->parseContext($capabilityId);
        foreach ($cache as $userId => &$capabilities) {
            if ($context) {
                unset($cache[$userId][$context]);
            }
            foreach ($capabilities as $capability => $value) {
                if (Str::startsWith($capability, $capabilityId)) {
                    unset($cache[$userId][$capability]);
                }
            }
        }
        \Cache::put(self::CACHE_KEY, $cache, 1440);
    }

    private function parseContext($capabilityId)
    {
        $idSplit = explode('.', $capabilityId);
        $context = false;
        if (count($idSplit) > 1) {
            $context = $idSplit[0];
        }
        return $context;
    }

}

