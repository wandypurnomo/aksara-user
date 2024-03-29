<?php

namespace Plugins\User\Repository;

use Aksara\TableView\TableRepository;
use Aksara\Support\EloquentRepository;
use Plugins\User\Models\User;

class UserRepository
    extends EloquentRepository implements TableRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}

