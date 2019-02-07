<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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

        Plugins\User\Models\Role::truncate();

        $role = Plugins\User\Models\Role::create([
            'name' => 'Admin User',
            'permissions' => [
                //manage user
                'master.list-user',
                'master.add-user',
                'master.edit-user',
                'master.delete-user',
                'master.add-user-role',
                'master.remove-user-role',

                //manage role
                'master.list-role',
                'master.add-role',
                'master.edit-role',
                'master.delete-role',

                // module manager
                'master.module-manager',
                'master.manage-modules',
            ],
        ]);

        $adminEmail = 'admin@gmail.com';

        $user = Plugins\User\Models\User::where('email', $adminEmail)->first();
        if (!$user) {
            $user = Plugins\User\Models\User::first();
        }
        if (!$user) {
            throw new \Exception("Admin user not found with email $adminEmail");
        }

        $user->roles()->attach($role);

        Model::reguard();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        echo "Set admin role for user =".$user->email."\n";

    }
}
