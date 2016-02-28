<?php

//Autoload classes
require('vendor/autoload.php');

//Database Connection
include 'config/db_config.php';
\Slim\Slim::registerAutoloader();

//Initiate a Slim instance
$app = new \Slim\Slim();
//$app->response->headers->set('Content-Type', 'application/json');

//Add Middleware for authentication
//Whitelisted routes
/*$app->add(new ExampleMiddleware([
    '/',
    '/sign_in',
    'sign_up',
    '/jobs',
]));*/

$app->hook('slim.after.router', "beforeResponse");
function beforeResponse(){
    //Solved some problems with JSON Objects
    ob_end_clean();
}



// route middleware for simple API authenticationdfgdfdfgdfgdfgdgdfgdgfdf
$app->get('/', function () use ($app) {
   // $u =  User::find(22078);
   
   //
   //$app->response->body($u->toJson());
   
    $pros = User::where('privilege','PRO')->get();
    $pros->sortByDesc(function($u){
                        return $u->jobs_awarded->count();
    });
    foreach ($pros as $pro){
        $pro->jobs_count = $pro->jobs_awarded->count();
    }

    $app->response->body($pros->toJson());
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
$app->post('/sign_in', function () use($app)
{
    $params = $app->request->params();
    $r = User::sign_in($params);
    $app->response->body($r);
});

//Sign Up
$app->post('/sign_up', function () use($app)
{
    $params = $app->request->params();
    $u = User::exists($params['email']);
    if($u==0) {//No Exsists
     //   $params['password']= $params['password'];//sha1($params['password']);//Encrypt password
        $r = User::sign_up($params);
        $app->response->body($r);
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
    ob_end_clean();
    $app->response->body($res);
});

//Get all pros
$app->get('/getPro', function () use($app) {

    $pros = User::where('privilege','PRO')->get();
    $pros->sortByDesc(function($u){
                        return $u->jobs_awarded->count();
    });
    foreach ($pros as $pro){
        $pro->jobs_count = $pro->jobs_awarded->count();
    }

    $app->response->body($pros->toJson());
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

    $app->response->body(Job::all()->toJson());


});

//Job Details
$app->get("/job/:job_id", function ($job_id) use ($app){
        $job = Job::find($job_id);
        $app->response->body($job->toJson());
});


//New job
$app->post('/job/new/', function () use($app) {
    $params = $app->request()->params();//Get all aprameters
    $r = Job::new_job($params);
    $app->response->body($r);
});

//Edit Job
$app->post('/job/update', function () use($app) {
    $params = $app->request()->params();
    $job=Job::edit_job($params);
    $app->response->body(json_encode($job));
});

//Edit Job
$app->post('/job/update/date', function () use($app) {
    $params = $app->request()->params();
    $job=Job::edit_job_dates($params);
    $app->response->body($job);
});


//Remove Job
$app->post('/job/delete/', function () use($app) {
    $id = $app->request()->params('id');
    $job = Job::find($id);
    $job->active = 0;
    $job->save();
});



//Off AYS
$app->post('/job/awarded_off_ays', function () use($app) {
    $params = $app->request()->params();//Get email
    $res = Job::awarded_off($params);
    ob_end_clean();
    $app->response->body($res);
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
    $f = Feedback::find($id);
    $f->delete();
    $app->response->body();

});

//Insert Feedback
$app->post("/feedback/set", function () use ($app) {

    $params = $app->request()->params();//Get all aprameters
    $set_feedback = JobOffers::set_bid();
    $app->response->body(json_encode($set_feedback));
});




//OFFERS

$app->post("/job/offer", function () use ($app) {

    $params = $app->request()->params();
    $new_bid =JobOffers::set_bid($params);
    $app->response->body(json_encode($new_bid));
});

$app->post("/job/get_offers", function () use ($app) {
    $params = $app->request()->params();
    $j = Job::find($params['id']);
    $j->jobOffers;
    $app->response->body($j->toJson());
});




//Run application
$app->run();