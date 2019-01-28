<?php

namespace Plugins\User\Repository;

use Aksara\TableView\TableRepository;
use Aksara\Support\EloquentRepository;
use App\User;

class RoleUserRepository
    extends EloquentRepository implements TableRepository
{
    public function setParentModel(User $user)
    {
        $this->model = $user->roles();
    }

    public function delete($id)
    {
        return $this->model->detach($id);
    }
}

