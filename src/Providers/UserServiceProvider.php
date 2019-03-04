<?php

namespace Plugins\User\Providers;

use Aksara\Providers\AbstractModuleProvider;
use Plugins\User\Models\Role;
use Plugins\User\Models\User;

class UserServiceProvider extends AbstractModuleProvider
{
    /**
     * Boot application services
     *
     * e.g, route, anything needs to be preload
     */
    protected function safeBoot()
    {
        \Eventy::addAction('aksara.init', function () {
            add_capability(__('user::page.user'), 'user');
            add_capability(__('user::labels.user_list'), 'list-user', 'user');
            add_capability(__('user::labels.add_user'), 'add-user', 'user');
            add_capability(__('user::labels.edit_user'), 'edit-user', 'user');
            add_capability(__('user::labels.delete_user'), 'delete-user', 'user');
            add_capability(__('user::labels.add_user_role'),
                'add-user-role', 'user');
            add_capability(__('user::labels.remove_user_role'),
                'remove-user-role', 'user');
        });

        \Eventy::addAction('aksara.init-completed', function () {

            $args = [
                'page_title' => __('user::page.user'),
                'menu_title' => __('user::menu.user'),
                'icon' => 'ti-user',
                'capability' => [ 'list-user' ],
                'route' => [
                    'slug' => '/aksara-user',
                    'args' => [
                        'as' => 'aksara-user',
                        'uses' => '\Plugins\User\Http\Controllers\UserController@index',
                    ],
                ]
            ];
            add_admin_sub_menu_route('aksara-menu-user', $args);


            $args = [
                'page_title' => __('user::page.edit_profile'),
                'menu_title' => __('user::menu.edit_profile'),
                'icon' => 'ti-user',
                'capability' => '',
                'route' => [
                    'slug' => '/aksara/user/edit-profile',
                    'args' => [
                        'as' => 'aksara.user.edit-profile',
                        'uses' => '\Plugins\User\Http\Controllers\UserController@editProfile',
                    ],
                ]
            ];
            add_admin_sub_menu_route('aksara-menu-user', $args);

            $userCreate = [
                'slug' => '/aksara-user/create',
                'method' => 'GET',
                'args' => [
                    'as' => 'aksara-user-create',
                    'uses' => '\Plugins\User\Http\Controllers\UserController@create',
                ],
            ];
            \AksaraRoute::addRoute($userCreate);
            $userStore = [
                'slug' => '/aksara-user/store',
                'method' => 'POST',
                'args' => [
                    'as' => 'aksara-user-store',
                    'uses' => '\Plugins\User\Http\Controllers\UserController@store',
                ],
            ];
            \AksaraRoute::addRoute($userStore);
            $userEdit = [
                'slug' => '/aksara-user/{id}/edit',
                'method' => 'GET',
                'args' => [
                    'as' => 'aksara-user-edit',
                    'uses' => '\Plugins\User\Http\Controllers\UserController@edit',
                ],
            ];
            \AksaraRoute::addRoute($userEdit);
            $userUpdate = [
                'slug' => '/aksara-user/{id}/update',
                'method' => 'PUT',
                'args' => [
                    'as' => 'aksara-user-update',
                    'uses' => '\Plugins\User\Http\Controllers\UserController@update',
                ],
            ];
            \AksaraRoute::addRoute($userUpdate);

            $userUpdate = [
                'slug' => '/aksara/user/update-profile',
                'method' => 'PUT',
                'args' => [
                    'as' => 'aksara.user.update-profile',
                    'uses' => '\Plugins\User\Http\Controllers\UserController@update',
                ],
            ];
            \AksaraRoute::addRoute($userUpdate);

            $userDestroy = [
                'slug' => '/aksara-user/{id}/destroy',
                'method' => 'GET',
                'args' => [
                    'as' => 'aksara-user-destroy',
                    'uses' => '\Plugins\User\Http\Controllers\UserController@destroy',
                ],
            ];
            \AksaraRoute::addRoute($userDestroy);


            $addRoleUser = [
                'slug' => '/aksara-user/{id}/roles',
                'method' => 'POST',
                'args' => [
                    'as' => 'aksara-user-add-role',
                    'uses' => '\Plugins\User\Http\Controllers\UserController@addRole',
                ],
            ];
            \AksaraRoute::addRoute($addRoleUser);

        });

        \Eventy::addAction('aksara.init-after-routes',function(){

            $args = [
                'position' => 5,
                'menuTitle' =>'Edit Profile',
                'capability' =>[],
                'url' => route('aksara.user.edit-profile'),
                'class' => 'fa fa-user'
            ];

            add_admin_menu_toolbar_dropdown($args);
        },20);

        \Eventy::addFilter('aksara.option.modules', function ($modules) {
            $modules[] = 'user';
            return $modules;
        });

        \Eventy::addAction('aksara.option.modules.user.render', function ($site_options) {
            $roles = Role::orderBy('name')->pluck('name', 'id');
            echo view('user::option.index', compact('roles', 'site_options'))->render();
        });

        \Eventy::addAction('aksara.auth-login-register.user-registered', function ($baseUser) {
            $user = User::find($baseUser->id);
            if (!$user) return;

            $options = get_options('site_options', []);

            if ($options && isset($options['default_role'])) {
                $defaultRole = $options['default_role'];
                $user->roles()->attach($defaultRole);
            }
        });
    }
}

