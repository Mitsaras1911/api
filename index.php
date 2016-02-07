<?php

//Autoload classes
require('vendor/autoload.php');

//Database Connection
include 'config/db_config.php';
\Slim\Slim::registerAutoloader();

//Initiate a Slim instance
$app = new \Slim\Slim();

//$app->add(new ExampleMiddleware());

//Server cross
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");


// route middleware for simple API authentication
$app->get('/', function () use ($app) {
    echo 'hi';
});

//----------------------------------------
//API STARTS HERE
//----- USER FUNCTIONS -------//
/**
 * Sign In - OK
 * Sign Up - OK
 * User Jobs - OK
 * Full User Details/Profile - OK
 * Edit Profile - OK
 * Delete User(Active =0) - OK
 * User has a field
 */


//Sign In
$app->post('/sign_in/', function () use($app)
{

    $params = $app->request->params();
    $r = User::sign_in($params);
    $app->response->body( json_encode($r));

});


//Sign Up
$app->post('/sign_up/', function () use($app)
{
    $params = $app->request->params();
    $exists = User::exists($params['email']);
    if($exists==0) {//No Exsists
        $params['password']= sha1($params['password']);
        $r = User::sign_up($params);
        $app->response->body(json_encode($r));
    }
    else {
        $app->response->body(json_encode(["error" => "exists"]));
    }
});


//User Jobs
$app->get("/user/jobs/:user_id", function ($user_id) use ($app) {
    UserAuth::new_key($user_id);
    $u = User::find($user_id);//Find User
    $u->jobs;
    $u->userToken;
   $app->response->body($u->toJson());
});

//Full User Details/Profile
$app->post("/user/profile/", function () use ($app) {
  $user_id = $app->request->params('user_id');
   // $token = $app->request->params('token');
   // UserAuth::authenticate($user_id,$token);//Authenticate or Fail
    $u = User::find($user_id);//Find User
    //$u->userToken;
    $app->response->body($u->toJson());
});

//Edit User Profile
$app->post("/user/profile/update/", function () use ($app) {
    $params = $app->request->params();
    $update = User::update_user($params);
    $update->token;
    $app->response->body(json_encode($update->toJson()));
});

//Disable User - Active 0
$app->post('/user/disable', function () use($app) {
    $user_id = $app->request()->params('user_id');//Get email
    $u = new User();
    if($u->find($user_id)->update(['active'=>0]))
        $app->response->body(json_encode(['status'=>http_response_code(202)]));
    else
        $app->response->body(json_encode(['status'=>http_response_code(500)]));
});

//Check Email - For register
$app->post('/email', function () use($app) {
    $email = $app->request()->params('email');//Get email
    $res = User::exists($email);
    $app->response->body($res->toJson());
});

//Get all pros
$app->get('/getPro', function () use($app) {
    //$res = User::where('privilege','PRO')->get();
    $res = new User();

    $res->jobs_awarded->all();
    $app->response->body($res->toJson());
});

//---------------------------//
//------JOBS FUNCTIONS-------//
/*
 * Get Jobs(all) - OK
 * Job details - OK
 * New Job - OK
 * Edit Job - OK
 * Delete Job - OK
 * Take offer -
 */

//Job by any condition
$app->get("/jobs", function () use ($app){
    $jobs = Job::paginate(2);
    //$app->response->body(Job::all()->toJson());
    return View::make('photos.show', array('photos' => $jobs));
    //$app->response->body(Job::paginate(2)->toJson());
});

//Job Details
$app->get("/job/:job_id", function ($job_id) use ($app){
    $j = Job::findOrFail($job_id);
    $j->get();
    $app->response->body($j->toJson());
});


//New job
$app->post('/job/new/', function () use($app) {
    $params = $app->request()->params();//Get all aprameters
    $r = Job::new_job($params);
    $user_id = $params['poster_id'];
    $phone = $params['phone'];
    User::where('id',$user_id)->update(["phone"=>$phone]);

    $app->response->body(json_encode($r));
});

//Edit Job
$app->post('/job/update', function () use($app) {
    $params = $app->request()->params();
    $job=Job::edit_job($params);
    $app->response->body($job);
});

//Remove Job
$app->post('/job/delete/', function () use($app) {
    $id = $app->request()->params('id');
    $job = Job::findOrFail($id);
    if($job->delete())
        return http_response_code(200);
    else
        return http_response_code(500);
});

$app->post('/job/offer/', function () use($app) {
    $params = $app->request()->params();
    $job = new Job();
    if($job->job_offer($params))
        return http_response_code(200);
    else
        return http_response_code(500);
});

//--------------------------//

//FeedBack Functions

//Get Feedback - OK
$app->get("/feedback/get/:job_id", function ($job_id) use ($app) {
    $r = Feedback::get_feedback($job_id);
    $app->response->body(json_encode($r));
});

//Update Feedback - OK
$app->post("/feedback/update/", function () use ($app) {

    $params = $app->request()->params();//Get all aprameters
    $r = Feedback::update_feedback($params);
    $app->response->body(json_encode($r));
});

//Delete Feedback - OK
$app->delete("/feedback/delete/:id", function ($id) use ($app) {
    if(Feedback::where('id',$id)->delete()){
        $app->response->body(json_encode(['resonse'=>http_response_code(200)]));
    }
});

//Insert Feedback
$app->post("/feedback/set", function () use ($app) {

    $params = $app->request()->params();//Get all aprameters
    $set_feedback = Feedback::set_feedback($params);
    $app->response->body(json_encode($set_feedback));
});

//Validate user

//Run application
$app->run();