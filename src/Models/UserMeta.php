<?php

namespace Plugins\User\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserMeta extends Model
{

    protected $table = 'user_meta';
    protected $fillable = ['meta_key', 'meta_value', 'user_id'];
    public $timestamps = false;

    public function validate($data)
    {
        $rules = [
            'meta_key' => 'required|max:40',
            'user_id' => 'required',
        ];

        return \Validator::make($data, $rules);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // Function for delete user meta data
    public static function delete_user_meta($userID = false, $key = false)
    {
        //Checking user_id data can't be empty
        if (!$userID)
        {
            return FALSE;
        } else {
            //Checking User data
            $user = User::find($userID);
            if (!$user)
                return FALSE;
        }

        //Checking key data
        if ($key)
        {
            // Get user meta data from db
            $user_meta = UserMeta::where('user_id', $userID)->where('meta_key', $key)->first();
            // Checking user meta data
            if ($user_meta)
            {
                // Delete user meta data if data valid
                $user_meta->delete();
            } else {
                return FALSE;
            }
        } else {
            // Get all user meta data from db
            $user_meta = UserMeta::where('user_id', $userID);
            // Checking user meta data
            if ($user_meta->count())
            {
                // Delete user meta data if data valid
                $user_meta->delete();
            } else {
                return FALSE;
            }
        }


        return TRUE;
    }

    // Function for get user meta data
    public static function get_user_meta($userID = false, $key = false, $unserialize = false)
    {
        // Checking user_id data can't be empty
        if (!$userID)
        {
            return FALSE;
        } else {
            // Checking user data
            $user = User::find($userID);
            if (!$user)
                return FALSE;
        }

        // Checking key data can't be empty
        if (!$key)
        {
            return FALSE;
        }

        // Get user meta data from db
        $user_meta = UserMeta::where('user_id', $userID)->where('meta_key', $key)->first();
        $data = '';

        // Checking user meta data
        if ($user_meta)
        {
            // Checking value data to unserialize or change string data to array data
            if ($unserialize)
            {
                $data = unserialize($user_meta->meta_value);
            } else {
                $data = $user_meta->meta_value;
            }
        } else {
            return FALSE;
        }
        return $data;
    }

    // Function for setting user meta data
    public static function set_user_meta($userID = false, $key = false, $value = false, $serialize = false)
    {
        // Checking user_id data can't be empty
        if (!$userID)
        {
            return FALSE;
        } else {
            // Checking user data
            $user = User::find($userID);
            if (!$user)
                return FALSE;
        }

        // Checking key data can't ne empty
        if (!$key)
        {
            return FALSE;
        }

        // Get user meta data from db
        $user_meta = UserMeta::where('user_id', $userID)->where('meta_key', $key)->first();
        $data = '';

        // Checking value data to unserialize or change array data to string data
        if ($serialize)
        {
            $data = serialize($value);
        } else {
            $data = $value;
        }

        $save = [
            'meta_key' => $key,
            'meta_value' => $data,
            'user_id' => $userID
        ];

        // Checking user meta data
        if ($user_meta)
        {
            // Checking data input
            $validator = $user_meta->validate($save);
            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }

            // Checking value data
            if ($data)
            {
                $user_meta->meta_key = $save['meta_key'];
                $user_meta->meta_value = $save['meta_value'];
                $user_meta->user_id = $save['user_id'];

                // Update user meta data
                if ($user_meta->save())
                    return TRUE;
                else
                    return FALSE;
            } else {
                // Delete user meta data if value data empty
                if ($user_meta->delete())
                    return TRUE;
                else
                    return FALSE;
            }
        } else {
            // If user meta data not in db, create new data
            $user_meta = new UserMeta;
            // Checking data input
            $validator = $user_meta->validate($save);
            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }
            $user_meta->meta_key = $save['meta_key'];
            $user_meta->meta_value = $save['meta_value'];
            $user_meta->user_id = $save['user_id'];
            if ($user_meta->save())
                return TRUE;
            else
                return false;
        }
    }

}
