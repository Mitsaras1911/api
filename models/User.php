<?php
/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 9/17/2015
 * Time: 4:43 PM
 */
class User extends Illuminate\Database\Eloquent\Model{
    public $timestamps = false;
    protected $table = 'user';//Define table name
    protected $fillable = ['id'];//Do not change id field
    protected $hidden = ['password'];

    //Do not return password field

    //Define the relationships

    //User has many Jobs
    public function jobs(){
        return $this->hasMany('Job','poster_id','id');
    }

    //Pro has many jobs awarded
    public function jobs_awarded(){

        return $this->hasMany('Job','awarded_to','id');
    }

    //User has only one Token
    public function userToken(){
        return $this->hasOne('UserAuth', 'user_id', 'id');//hasOne('table', 'foreignKey', 'hostKey')
    }

    //User has many feedbacks
    public function user_feedback(){
        return $this->hasMany('Feedback','from','id');
    }

    //User Sign In
    static function sign_in($params){
        $r = User::query()
            ->where('email',$params['email'])
            ->where('password',$params['pass'])
            ->get();//)->toArray();//sha1($params['pass']))
        if($r->count()==1){
            $r = User::find($r[0]->id);
            $r->token='asd';
            $r->status=1;
        }
        else{
            $r = new User();
            $r -> status = 0;
            $r -> error = 'INV_CREDITS';
        }
        return $r;
    }
    //User Sign Up
    static function sign_up($params){
        if (filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            $newUser = User::insertGetId($params);
            return $newUser;
        }
        else {
            return ["status" => 0,
                "error" => "INV_EMAIL"];
        }
    }
    //Email exists?
    static function exists($email){
        return User::query()->where('email', $email)->get()->count();
    }

    //Fetch Use's Jobs
    static function get_user_jobs($user_id){
        $id = User::find($user_id);
        $jobs = Job::query()
            ->where('poster_id', $id)
            ->get();
        echo 'hello';
        return $jobs;

    }

    //Update User Profile
    static function update_user($params){
        //$user = User::where ("id",1); // note that this shortcut is available if the comparison is =
        $update = User::where('id', $params['id'])->update($params);
        if ($update!=null) {
            return ['status' => http_response_code(200)];
        }
    }






}



