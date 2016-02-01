<?php
/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 10/31/2015
 * Time: 10:57 AM
 */

class Feedback extends Illuminate\Database\Eloquent\Model{
    protected $table = "feedback";
    protected $guarded = ['id'];

    //Define the relationships






    //Functions


    public static function get_feedback($job_id){
        $set_feedback = Feedback::where('job_id',$job_id)
            ->orderBy('date', 'DESC')
            ->first();
        return $set_feedback;
    }

    public static function update_feedback($params){
        $params['date'] = date('Y-m-d h:i:s', time());
        $feedback = Feedback::where('id', $params['id']);
        $feedback->update($params);
        $details = $feedback->get();
        return $details;
    }

    public static function set_feedback($params){
        $insert_feedback = Feedback::insertGetId($params);
        return $insert_feedback;
    }


    //Define the relationship(1->oo) betwen jobs and job_feedback
    public function feedback(){
        return $this->hasMany('Job','poster_id','id');
    }

}
