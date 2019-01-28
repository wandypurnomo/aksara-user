<?php

namespace Plugins\User\Presenters;

//role repository
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Plugins\User\Http\Controllers\RoleUserTable;
use Plugins\User\Repository\RoleRepository;
use Plugins\User\Repository\UserRepository;
use App\Role;

class UserForm
{
    private $userRepo;
    private $roleRepo;
    private $tableController;

    public function __construct(
        UserRepository $userRepo,
        RoleRepository $roleRepo,
        RoleUserTable $tableController
    ){
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
        $this->tableController = $tableController;
    }

    public function edit($id, Request $request)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return false;
        }

        $userRole = Role::orderBy('name')->get()->pluck('name', 'name');

        $selectRole = [];
        foreach ($this->roleRepo->allDetached(
            'users', $user->id) as $role) {
            $selectRole[$role->id] = $role->name;
        }

        $this->tableController->setParentModel($user);
        $table = $this->tableController->handle($request);

        if ($table instanceof RedirectResponse) {
            return $table;
        }

        return [
            'user' => $user,
            'select_role' => $selectRole,
            'table' => $table,
            'user_role' => $userRole,
        ];
    }
}

