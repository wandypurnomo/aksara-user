<?php

namespace Plugins\User\Repository;

use Aksara\TableView\TableRepository;
use Aksara\Support\EloquentRepository;
use App\Role;

class RoleRepository
    extends EloquentRepository implements TableRepository
{
    public function __construct(Role $model)
    {
        $this->model = $model;
    }
}

