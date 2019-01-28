<?php
namespace Plugins\User\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        //    $this->authorize('user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        authorize('list-role');

        $roles = Role::orderBy('name');
        if ($request->get('bapply')) {
            if ($request->input('apply')) {
                $apply = $request->input('apply');
                if ($apply == 'destroy') {
                    if ($request->input('role_id')) {
                        $role_id = $request->input('role_id');
                        foreach ($role_id as $v) {
                            $this->destroy($v);
                        }
                    }
                }
            }
        }
        if ($request->input('search')) {
            $search = $request->input('search');
            $roles = $roles->where('name', 'like', '%' . $search . '%');
        } else {
            $search = '';
        }

        $total = $roles->count();
        $roles = $roles->paginate(10);

        return view('user::role.index', compact('roles', 'search', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        authorize('add-role');

        $role = new Role();
        $role->permissions = [];
        $context = $request->get('context') ?? 'master';
        $capabilities = \RoleCapability::allInContext($context);
        return view('user::role.create', compact('context', 'role', 'capabilities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        authorize('add-role');

        $role = new Role();

        $validator = $role->validate($request->all());

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $v) {
                admin_notice('danger', $v[0]);
            }
            return redirect()->route('aksara-role-create')
                            ->withErrors($validator)
                            ->withInput();
        }
        $data = $request->all();
        $role->name = $data['name'];
        $context = @$data['context'] ?? 'master';
        $permissions = $data['permissions'] ?? [];
        $role->permissions = $this->addContextSuffix($context, $permissions);
        $role->save();
        admin_notice('success', __('user::messages.success_add_role'));
        return redirect()->route('aksara-role');
    }

    private function addContextSuffix($context, &$permissions)
    {
        foreach ($permissions as &$permission) {
            $permission = $context.'.'.$permission;
        }
        return $permissions;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        authorize('edit-role');

        $role = Role::find($id);

        $context = $request->get('context') ?? 'master';
        $capabilities = \RoleCapability::allInContext($context);

        return view('user::role.edit', compact('context', 'role', 'capabilities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        authorize('edit-role');

        $role = Role::find($id);
        $validator = $role->validate($request->all(), $id);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $v) {
                admin_notice('danger', $v[0]);
            }
            return back()->withErrors($validator)
                         ->withInput();
        }
        $data = $request->all();
        $role->name = $data['name'];
        $permissions = @$data['permissions'] ?? [];
        $context = @$data['context'] ?? 'master';
        $role->permissions = $this->updateContextPermissions(
            $context, $this->addContextSuffix($context, $permissions), $role->permissions);
        $role->save();
        admin_notice('success', __('user::messages.success_update_role'));
        return redirect()->route('aksara-role');
    }

    private function updateContextPermissions(
        $context, $updatedPermissions,
        $oldPermissions
    ){
        foreach ($oldPermissions as $key => $oldPermission) {
            if (\Strings::startsWith($oldPermission, "$context.")) {
                unset($oldPermissions[$key]);
            }
        }

        $newPermissions = array_merge($oldPermissions, $updatedPermissions);
        return $newPermissions;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        authorize('delete-role');

        $role = Role::find($id);
        if (!$role) {
            admin_notice('danger', __('user::messages.error.role_not_found'));
            return redirect()->route('aksara-role');
        }
        if ($role->delete()) {
            admin_notice('success', __('user::messages.success_delete_role'));
        } else {
            admin_notice('danger', __('user::messages.error.role_not_deleted'));
        }
        return redirect()->route('aksara-role');
    }
}
