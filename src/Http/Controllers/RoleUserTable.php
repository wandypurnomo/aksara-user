<?php

namespace Plugins\User\Http\Controllers;

use Aksara\TableView\Controller\AbstractTableController;
use Aksara\TableView\Controller\Concerns\HasDestroyAction;
use Plugins\User\Repository\RoleUserRepository;
use Plugins\User\Presenters\RoleUserTablePresenter;
use Plugins\User\Models\User;

class RoleUserTable extends AbstractTableController
{
    use HasDestroyAction;

    public function __construct(
        RoleUserRepository $repo,
        RoleUserTablePresenter $table
    ){
        parent::__construct($repo, $table);
    }

    public function setParentModel(User $user)
    {
        $this->repo->setParentModel($user);
    }

    protected function registerActions()
    {
        $this->registerDestroyAction();
    }

}

