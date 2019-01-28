<?php

namespace Plugins\User\Repository;

use Aksara\TableView\TableRepository;
use Aksara\Support\EloquentRepository;
use App\User;

class UserRepository
    extends EloquentRepository implements TableRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}

