<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Plugins\User\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Model::unguard();

        //get or create default role by name Admin User
        $adminRole = Role::firstOrCreate([ 'name' => 'Admin User' ]);

        $permissions = [
            //manage user
            'master.user',
            'master.list-user',
            'master.add-user',
            'master.edit-user',
            'master.delete-user',
            'master.add-user-role',
            'master.remove-user-role',

            //manage role
            'master.role',
            'master.list-role',
            'master.add-role',
            'master.edit-role',
            'master.delete-role',

            // module manager
            'master.module-manager',
            'master.manage-modules',
        ];

        $permissionDatas = collect($permissions)->map(function ($item) {
            return [
                'permission' => $item,
            ];
        })->all();

        foreach ($permissionDatas as $permissionData) {
            $adminRole->permissions()
                      ->updateOrCreate($permissionData, []);
        }

        $adminEmail = 'admin@gmail.com';

        $user = Plugins\User\Models\User::where('email', $adminEmail)->first();
        if (!$user) {
            $user = Plugins\User\Models\User::first();
        }
        if (!$user) {
            throw new \Exception("Admin user not found with email $adminEmail");
        }

        //if already attached then just skip it
        if (!$user->roles->contains($adminRole)) {
            $user->roles()->attach($adminRole);
        }

        Model::reguard();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        echo "Set admin role for user =".$user->email."\n";

    }
}
