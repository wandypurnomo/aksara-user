<?php

namespace Plugins\User\UserCapability;

interface CapabilityCache
{
    public function put($userId, $capabilityId, $args, $capable);
    public function has($userId, $capabilityId, $args = []);
    public function get($userId, $capabilityId, $args = []);
    public function flushUser($userId);
    public function flushCapability($capabilityId);

}
