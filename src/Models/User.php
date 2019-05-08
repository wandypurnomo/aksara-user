<?php

namespace Plugins\User\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Plugins\User\Notifications\ResetPassword;
use Aksara\Extensions\Laravel\Relations\Traits\PivotEventTrait;

class User extends Authenticatable
{
    use Notifiable;
    use PivotEventTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function validate($data)
    {
        $id = $this->id ?? null;

        if ($id != null) {
            if (isset($data['password']) || isset($data['password_confirmation']))
                $rules = [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'password' => 'required|confirmed',
                    'password_confirmation' => 'required'
                ];
            else
                $rules = [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users,email,' . $id,
                ];
        } else {
            $rules = [
                'name' => 'required|string',
                'email' => 'required|unique:users|email',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ];
        }

        return \Validator::make($data, $rules);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function scopeHasRole($query, $roleId)
    {
        return $this->whereHas('roles', function ($roles) use ($roleId) {
            $roles->where('id', $roleId);
        });
    }

    public function scopeHasCapability($query, $capability, $context = null)
    {
        $context = $context ?? 'master';

        return $this->whereHas('roles', function ($roles) use ($context, $capability) {
            $roles->whereHas('permissions', function ($permissions) use ($context, $capability) {
                $permissions->where('permission', $context.'.'.$capability);
            });
        });
    }

    public static function destroyUser($id)
    {
        if(!is_array($id)){
            $id = [$id];
        }

        $success = false;

        foreach ($id as $userID) {
            $user = User::find($userID);
            if ($userID != \Auth::user()->id) {
                if ($user->delete()) {
                    $success = true;
                }
            }
        }
        if (!$success) {
            return false;
        }
        return count($id);
    }

    public static function boot()
    {
        parent::boot();

        static::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            //publish event role added
            \Eventy::action('aksara.user.role.added', $model->id);
        });

        static::pivotDetached(function ($model, $relationName, $pivotIds) {
            //publish event role removed
            \Eventy::action('aksara.user.role.removed', $model->id);
        });
    }
}
