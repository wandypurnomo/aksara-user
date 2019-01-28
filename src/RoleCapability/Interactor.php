<?php
namespace Plugins\User\RoleCapability;

use Aksara\Support\Strings;
use Aksara\Support\Arrays;

//TODO refactor
//extract DTO's
class Interactor implements RoleCapabilityInterface
{
    private $configRepo;
    private $stringHelper;
    private $arrayHelper;

    public function __construct(
        ConfigRepository $configRepo,
        Strings $stringHelper,
        Arrays $arrayHelper
    ){
        $this->configRepo = $configRepo;
        $this->stringHelper = $stringHelper;
        $this->arrayHelper = $arrayHelper;
    }

    public function addWithContext($context, $name, $id = false, $parent = false, $callback = null)
    {
        if (!$context) {
            throw new \Exception('context name not set');
        }

        if (!$name) {
            throw new \Exception('capability name not set');
        }

        // burn !
        if (!$id) {
            // id should not be created from name
            // it should be provided by the caller
            // on the contrary, name can be generated from id
            $id = $this->stringHelper->slug($name);
        } else {
            // id format should be as-is
            // if must be slug, then validate, don't convert
            $id = $this->stringHelper->slug($id);
        }

        $capabilities = $this->configRepo->get("aksara.user.capabilities.$context", []);

        if ($parent) {
            if (!isset($capabilities[$parent])) {
                throw new \Exception('Parrent '.$parent.' is not defined');
            }

            $capabilities[$parent]['capabilities'][$id]['name'] = $name;
            $capabilities[$parent]['capabilities'][$id]['callback'] = $callback;
        } else {
            if (isset($capabilities[$id])) {
                throw new \Exception('capability '.$id.' is already defined');
            }

            $capabilities[$id] = [
                    'name' => $name,
                    'callback' => $callback,
                    'capabilities' => []
                ];
        }

        $this->configRepo->set("aksara.user.capabilities.$context", $capabilities);

        return true;
    }

    public function add($name, $id = false, $parent = false, $callback = null)
    {
        return $this->addWithContext('master', $name, $id, $parent, $callback);
    }

    public function getWithContext($context, $id)
    {
        $capability =  $this->arrayHelper->searchValueRecursive($id,
            $this->configRepo->get("aksara.user.capabilities.$context", []));

        if (!$capability) {
            throw new \Exception('capability '.$id.' is not defined');
        }

        return $capability;
    }

    public function get($id)
    {
        return $this->getWithContext('master', $id);
    }

    public function allInContext($context)
    {
        $capabilities = $this->configRepo->get("aksara.user.capabilities.$context", []);
        return $capabilities;
    }

    public function all()
    {
        return $this->allInContext('master');
    }
}

