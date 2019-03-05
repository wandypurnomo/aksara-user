<?php

namespace Plugins\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Plugins\User\Models\User;
use Plugins\User\Models\Role;
use Auth;
use Plugins\User\Presenters\UserForm;
use Plugins\User\Presenters\UserListTable;
use Plugins\User\Http\Requests\AddRoleUserRequest;
use Plugins\User\Repository\UserRepository;

class UserController extends Controller
{
    private $repo;
    private $form;

    public function __construct(
        UserRepository $repo,
        UserForm $form
    ){
        $this->repo = $repo;
        $this->form = $form;
    }

    public function index(Request $request)
    {
        authorize('list-user');

        if ($request->get('bapply')) {
            if ($request->input('apply')) {
                $apply = $request->input('apply');
                if ($apply == 'destroy') {
                    if ($id = $request->input('id')) {
                        return $this->destroy($id);
                    }
                }
            }
        }

        $users = User::orderBy(
            $request->input('sort_by') ?? 'name',
            $request->input('sort_order') ?? 'ASC'
        );

        if ($status = $request->input('status')) {
            switch (strtolower($status)) {
            case 'active': $users = $users->where('active', 1); break;
            case 'inactive': $users = $users->where('active', 0); break;
            }
        }

        if ($search = $request->input('search') ?? '') {
            $users = $users->where('name', 'like', '%' . $search . '%')
                           ->orWhere('email', 'like', '%'.$search.'%');
        }

        $isActive = $request->input('is_active');

        if (!is_null($isActive) && $isActive != '') {
            $users = $users->where('active', $isActive);
        }

        $perPage = $request->input('per_page') ?? 10;
        $userPaginator = $users->paginate($perPage);

        $presenter = new UserListTable(
            $userPaginator,
            $request
        );

        return view('user::user.index', compact('presenter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        authorize('add-user');

        $user = new User();
        $user_role = Role::orderBy('name')->get()->pluck('name', 'name');
        return view('user::user.create', compact('user', 'user_role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        authorize('add-user');

        $user = new User();

        $validator = $user->validate($request->all(), false);
        $validator->validate();

        $data = $request->all();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->active = $data['active'];
        $user->save();

        $this->storeProfilePicture($request, $user);

        admin_notice('success', __('user::messages.success_add_user'));
        return redirect()->route('aksara-user-edit', $user->id);
    }

    private function storeProfilePicture(Request $request, $user)
    {
        if ($request->file('profile_picture')) {

            $validator = \FileUploadValidator::make($request, 'profile_picture', [
                'image/jpeg', 'image/png',
            ]);
            $validator->validate();

            $image = \FileUploader::handle($request, null, "profiles/$user->id", 'profile_picture');

            set_user_meta($user->id, 'profile_picture', $image->getPath());
        } elseif ($request->input('profile_picture_deleted')) {
            //remove profile picture field
            set_user_meta($user->id, 'profile_picture', '');
        }
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
        authorize('edit-user');

        $viewData = $this->form->edit($id, $request);
        if (!$viewData) {
            abort(404, 'Not found');
        }
        if ($viewData instanceof RedirectResponse) {
            return $viewData;
        }
        return view('user::user.edit', $viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    {
        $user = \Auth::user();
        $user_role = Role::orderBy('name')->get()->pluck('name', 'name');
        return view('user::user.edit-profile', compact('user', 'user_role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = false)
    {
        if ($id !== false) {
            authorize('edit-user');
        }

        $id === false ? $id = \Auth::user()->id : $id;

        $user = User::find($id);

        if ($request->input('password') || $request->input('password_confirmation')) {
            $data = [
                'id' => $id,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
                'active' => $request->input('active')
            ];
        } else {
            $data = [
                'id' => $id,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'active' => $request->input('active')
            ];
        }

        $validator = $user->validate($data, false);
        $validator->validate();

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['password'])) {
            $user->password = $data['password'];
        }

        if (isset($data['active'])) {
            $user->active = $data['active'];
        }
        $user->save();

        $this->storeProfilePicture($request, $user);

        admin_notice('success', __('user::messages.success_update_user'));

        if ($id === false) {
            return redirect()->route('aksara.user.edit-profile');
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        authorize('delete-user');

        $count = User::destroyUser($id);
        if (!$count) {
            admin_notice('danger', 'Data gagal dihapus.');
        } else {
            admin_notice('success', $count.' data berhasil dihapus. ');
        }

        return redirect()->route('aksara-user');
    }

    public function addRole($id, AddRoleUserRequest $request)
    {
        authorize('add-user-role');

        $roleId = $request->input('role_id');
        $success = $this->repo->attachOnce($id, 'roles', $roleId);
        if (!$success) {
            admin_notice('danger', __('user::messages.add_role_failed'));
        } else {
            admin_notice('success', __('user::messages.add_role_success'));
        }
        return redirect()->route('aksara-user-edit', $id);
    }
}
