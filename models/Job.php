<?php
/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 9/30/2015
 * Time: 12:44 PM
 */

class Job extends Illuminate\Database\Eloquent\Model
{

    protected $table = 'job';
    public $timestamps = false;
    protected $fillable = ['id', 'poster_id'];

    //Job has many feedbacks
    public function feedback(){
        return $this->hasMany('Feedback', 'job_id', 'id');
    }

    public function jobCategory(){
        return $this->hasOne('JobCategory','id','categoryid');
    }


    //Create new Job
    public static function new_job($params){
        $params['date_job_completion'] = Job::set_date($params['date_job_completion']);
        $job = Job::insertGetId($params);
        $j = Job::find($job);
        $j->date_posted = date('Y-m-d');
        $j->save();
        return $job;
    }

    public static function set_date($date){
        switch ($date){
            case 0://ASAP 2 weeks
                $ins_date = date('Y-m-d',strtotime("+2 weeks"));
                break;
            case 1://Next Month
                $ins_date = date('Y-m-d',strtotime("+1 month"));
                break;
            case 3://Not Sure - 1 month
                $ins_date = date('Y-m-d',strtotime("+1 month"));
                break;
            case 4://Urgent - 1 week
                $ins_date = date('Y-m-d',strtotime("+1 weeks"));
                break;
            default://Specific Date
                $ins_date = $date;



        }
        return $ins_date;
    }


    //Edit Job
    public static function edit_job($params){
        $job = Job::find($params['id']);

        if ($job->update($params)) {
            return ['status' => http_response_code(202)];
        } else {
            return ['status' => http_response_code(500)];
        }
    }

    //Take offer
    public static function job_offer($params){
        $job = Job::find($params['id']);
        if ($job->update($params)) {
            return ['status' => http_response_code(202)];
        } else {
            return ['status' => http_response_code(500)];
        }
    }
}




