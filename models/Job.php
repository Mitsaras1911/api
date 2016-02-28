<?php
/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 9/30/2015
 * Time: 12:44 PM
 */
use Carbon\Carbon;
class Job extends Illuminate\Database\Eloquent\Model
{

    protected $table = 'job';
    public $timestamps = false;
    protected $fillable = ['id', 'poster_id'];

    public static function edit_job_dates($params)
    {
        $j = Job::find($params['job_id'],['date_job_completion']);
        $a = new DateTime($j->date_job_completion);
        $a->modify('+1 week');
        $j->date_job_completion = $a->format("Y-m-d");
        $j->save();
        return $j;
    }

    public static function awarded_off($params)
    {
        $job = Job::find($params['id']);
        $job->awarded_to = 12974;
        $job->save();
        return 1;
    }

    //Job has many feedbacks
    public function feedback(){
        return $this->hasMany('Feedback', 'job_id', 'id');
    }

    //Job has many offers/bids
    public function jobOffers(){
        return $this->hasMany('JobOffers', 'job_id', 'id');
    }

    public function jobCategory(){
        return $this->hasOne('JobCategory','id','categoryid');
    }


    //Create new Job
    public static function new_job($params){
        $jobs =Job::where('poster_id',$params['poster_id'])
            ->where('title',$params['title'])
            ->where('description',$params['description'])
            ->get();
        if($jobs->count()==0) {
            $params['date_job_completion'] = Job::set_date($params['date_job_completion']);
            $job = Job::insertGetId($params);
            $j = Job::find($job);
            $j->date_posted = date('Y-m-d');
            $j->date_bid_deadline = date('Y-m-d H:i:s', strtotime("+1 month"));
            $j->save();
            return $job;
        }
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
        $job = Job::where('id',$params['id'])->update($params);//find($params['id']);
        if($job){
            return ['status'=>1];
        }
        else{
            return ['status'=>0];
        }
    }

    //Take offer
    public static function job_offer($params)
    {
        $job = Job::find($params['id']);
        $job->update($params);
        if($job->save())
        {
            return 1;
        }
        else{
            return 0;
        }
    }
}




