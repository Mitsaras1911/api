<?php

/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 2/17/2016
 * Time: 3:19 PM
 */
class JobOffers extends Illuminate\Database\Eloquent\Model{
    protected $table = 'bid';
    public $timestamps = false;
    protected $fillable = ['id'];

    public static function set_bid($params)
    {
        $new_bid = JobOffers::insertGetId($params);
        return $new_bid;
    }


}