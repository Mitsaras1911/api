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



    //Create user token
    public static function new_key($user_id)
    {
        $u = UserAuth::firstOrNew([
            'user_id' => $user_id
        ]);
        $u->token = UserAuth::getGUID();
        $u->last_date_authorized = date("Y-m-d H:i:s");
        $u->save();
    }

    //Generate Access Token
    public static function authenticate($token){
        $u = UserAuth:: query()
                        ->where('token', $token)
                        ->get();
        if ($u->count()==1){
            return true;
        }
        else {
            return false;
        }
    }
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
}