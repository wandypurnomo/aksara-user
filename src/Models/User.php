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
        if ($this->id) {
            if (isset($data['password']) || isset($data['password_confirmation']))
                $rules = [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users' . ($this->id ? ",id,$this->id" : ''),
                    'password' => 'required|confirmed',
                    'password_confirmation' => 'required'
                ];
            else
                $rules = [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users' . ($this->id ? ",id,$this->id" : ''),
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
        $roles = $this->belongsToMany(Role::class);
        return $roles;
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
