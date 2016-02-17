<?php

/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 2/17/2016
 * Time: 4:50 PM
 */
class UserStars extends Illuminate\Database\Eloquent\Model{

    protected $table = 'job';
    public $timestamps = false;
    protected $fillable = ['id', 'poster_id'];




}