<?php

/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 2/7/2016
 * Time: 1:13 PM
 */
class JobAwards extends Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;
    protected $table = 'job_awarded';//Define table name
    protected $fillable = ['id'];//Do not change id field

    public function awards(){
        return $this->hasMany('Job','poster_id','awarded_to');
    }


}