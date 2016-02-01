<?php

/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 1/11/2016
 * Time: 11:33 AM
 */
class UserAuth extends  Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;
    protected $table = 'user_auth';//Define table name
    protected $fillable = ['token', 'user_id'];

    public function userKey($user_id, $token)
    {
        return $this->hasOne('User', 'user_id', 'user_id');
    }


    //Create user token
    public static function new_authenticate($user_id)
    {
        $u = UserAuth::firstOrNew([
            'user_id' => $user_id
        ]);
        $u->token = UserAuth::getGUID();
        $u->last_date_authorized = date("Y-m-d H:i:s");
        $u->save();
        return $u->token;
    }

    //Generate Access Token
    public static function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $uuid = substr($charid, 0, 8)
                . substr($charid, 8, 4)
                . substr($charid, 12, 4)
                . substr($charid, 16, 4)
                . substr($charid, 20, 12);
            return $uuid;
        }
    }


    public static function authenticate($user_id, $token)
    {
        $u = UserAuth::query()->where('token', $token)->get();
        if (count($u)==0){
            return false;
        }
        else
            return UserAuth::new_authenticate($user_id);

        //TODO: Na rotiso to xatzi pos na kamo terminate to $app pu tutin tin function

    }
}