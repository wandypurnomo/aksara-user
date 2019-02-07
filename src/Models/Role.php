<?php

namespace Plugins\User\Models;

use Illuminate\Database\Eloquent\Model;
use Plugins\User\Models\User;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'permissions'];
    public $timestamps = false;

    public function validate($data, $except = null)
    {
        $rules = [
            'name' => 'required|unique:roles,name'.($except ? ','.$except : ''),
        ];

        return \Validator::make($data, $rules);
    }

    public function getPermissionsAttribute($value) {
        return @unserialize($value);
    }

    public function setPermissionsAttribute($value) {
        $this->attributes['permissions'] = @serialize($value);
    }

    public function user()
    {
        return $this->hasMany(User::class, 'role', 'name');
    }

    // Function for delete role data
    public static function delete_role($name = false)
    {
        //Checking key data
        if ($name)
        {
            // Get role data from db
            $role = Role::where('name', $name)->first();
            // Checking user meta data
            if ($role)
            {
                // Delete role data if data valid
                $role->delete();
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    // Function for get role data
    public static function get_role($name = false)
    {
        // Checking key data can't be empty
        if (!$name)
        {
            return FALSE;
        }

        // Get role data from db
        $role = Role::where('name', $name)->first();
        $data = '';
        // Checking role data
        if ($role)
        {
            // Checking value data to unserialize or change string data to array data
            $data = unserialize($role->permissions);
        } else {
            return FALSE;
        }
        return $data;
    }

    // Function for setting user meta data
    public static function set_role($name = false, $value = false)
    {
        // Checking key data can't ne empty
        if (!$name)
        {
            return FALSE;
        }

        // Get role data from db
        $role = Role::where('name', $name)->first();
        $data = serialize($value);

        $save = [
            'name' => $name,
            'permissions' => $data,

        ];

        // Checking role data
        if ($role)
        {
            // Checking data input
            $validator = $role->validate($save);
            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }

            // Checking value data
            if ($data)
            {
                $role->name = $save['name'];
                $role->permissions = $save['permissions'];

                // Update role data
                if ($role->save())
                    return TRUE;
                else
                    return FALSE;
            } else {
                // Delete role data if value data empty
                if ($role->delete())
                    return TRUE;
                else
                    return FALSE;
            }
        } else {
            // If role data not in db, create new data
            $role = new Role;
            // Checking data input
            $validator = $role->validate($save);
            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }
            $role->name = $save['name'];
            $role->permissions = $save['permissions'];
            if ($role->save())
                return TRUE;
            else
                return false;
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            \Eventy::action('aksara.role.saving', $model);
        });

        static::deleting(function ($model) {
            \Eventy::action('aksara.role.deleting', $model);
        });
    }
}
