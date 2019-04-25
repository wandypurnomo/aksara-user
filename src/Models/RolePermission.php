<?php

namespace Plugins\User\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';

    protected $fillable = [
        'role_id',
        'permission',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            \Eventy::action('aksara.role.permission.saved', $model);
        });

        static::deleted(function ($model) {
            \Eventy::action('aksara.role.permission.deleted', $model);
        });
    }
}


