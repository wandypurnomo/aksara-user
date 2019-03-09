<?php

namespace Plugins\User\UserCapability;

interface UserCapabilityInterface
{
    public function userHasCapability($userId, $capabilityId, $args = []);
    public function userHasContextCapability($userId, $context, $capabilityId, $args = []);
    public function hasAny($capabilities = []);
    public function hasAnyInContext($context, $capabilities = []);
    public function hasCapability($capabilityId, $args = []);
    public function hasContextCapability($context, $capabilityId, $args = []);
    public function hasContext($context);

    public function getUserCapabilities($userId);
    public function getUserGroupedCapabilities($userId);
}
