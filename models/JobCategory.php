<?php

/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 2/9/2016
 * Time: 7:39 PM
 */
class JobCategory extends Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;
    protected $table = 'job_categories';//Define table name
    protected $fillable = ['id'];//Do not change id field
    //protected $hidden = ['password']; No hidden fields

    //Job category can be applied to many Joba
    public function jobs(){
        return $this->hasMany('Job','categoryid	','id');
    }
}