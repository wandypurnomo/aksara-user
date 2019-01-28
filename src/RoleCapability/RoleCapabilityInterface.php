<?php
namespace Plugins\User\RoleCapability;

//TODO refactor
//extract DTO's
interface RoleCapabilityInterface
{
    public function addWithContext($context, $name, $id = false, $parent = false, $callback = null);
    public function add($name, $id = false, $parent = false, $callback = null);
    public function getWithContext($context, $id);
    public function get($id);
    public function allInContext($context);
    public function all();
}
