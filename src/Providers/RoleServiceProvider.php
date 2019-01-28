<?php
namespace Plugins\User\Providers;

use Aksara\Providers\AbstractModuleProvider;

class RoleServiceProvider extends AbstractModuleProvider
{
    /**
     * Boot application services
     *
     * e.g, route, anything needs to be preload
     */
    protected function safeBoot()
    {
        \Eventy::addAction('aksara.init', function () {
            add_capability(__('user::page.role'), 'role');
            add_capability(__('user::labels.role_list'), 'list-role', 'role');
            add_capability(__('user::labels.add_role'), 'add-role', 'role');
            add_capability(__('user::labels.edit_role'), 'edit-role', 'role');
            add_capability(__('user::labels.delete_role'), 'delete-role', 'role');
        });

        \Eventy::addAction('aksara.init-completed', function () {
            $args = [
                'page_title' => __('user::page.role'),
                'menu_title' => __('user::menu.role'),
                'icon' => 'ti-user',
                'capability' => [ 'list-role' ],
                'route' => [
                    'slug' => '/aksara-role',
                    'args' => [
                        'as' => 'aksara-role',
                        'uses' => '\Plugins\User\Http\Controllers\RoleController@index',
                    ],
                ]
            ];
            add_admin_sub_menu_route('aksara-menu-user', $args);

            $userCreate = [
                'slug' => '/aksara-role/create',
                'method' => 'GET',
                'args' => [
                    'as' => 'aksara-role-create',
                    'uses' => '\Plugins\User\Http\Controllers\RoleController@create',
                ],
            ];
            \AksaraRoute::addRoute($userCreate);
            $userStore = [
                'slug' => '/aksara-role/store',
                'method' => 'POST',
                'args' => [
                    'as' => 'aksara-role-store',
                    'uses' => '\Plugins\User\Http\Controllers\RoleController@store',
                ],
            ];
            \AksaraRoute::addRoute($userStore);
            $userEdit = [
                'slug' => '/aksara-role/{id}/edit',
                'method' => 'GET',
                'args' => [
                    'as' => 'aksara-role-edit',
                    'uses' => '\Plugins\User\Http\Controllers\RoleController@edit',
                ],
            ];
            \AksaraRoute::addRoute($userEdit);
            $userUpdate = [
                'slug' => '/aksara-role/{id}/update',
                'method' => 'PUT',
                'args' => [
                    'as' => 'aksara-role-update',
                    'uses' => '\Plugins\User\Http\Controllers\RoleController@update',
                ],
            ];
            \AksaraRoute::addRoute($userUpdate);
            $userDestroy = [
                'slug' => '/aksara-role/{id}/destroy',
                'method' => 'GET',
                'args' => [
                    'as' => 'aksara-role-destroy',
                    'uses' => '\Plugins\User\Http\Controllers\RoleController@destroy',
                ],
            ];
            \AksaraRoute::addRoute($userDestroy);
        });
    }
}
