<?php

/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 2/1/2016
 * Time: 12:17 PM
 */
class ExampleMiddleware extends Slim\Middleware {

    public function __construct() {
        //Define the urls that you want to exclude from Authentication, aka public urls
        $this->whiteList =['/user/profile'];
    }

    public function call(){
        // Get reference to application
        $app = $this->app;
        $token = $this->app->request->params('token');
        $user_id = $this->app->request->params('user_id');
        $res = UserAuth::authenticate($token);
        if ($res){
            //Found User

            $this->app->response->body();//->toJson();//write('Bar');
        }
        else {
            $this->app->response->body(json_encode(['error' => 'denied']));
         //   exit();
        }
        $this->next->call();

    }
}