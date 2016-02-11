<?php

/*
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 2/1/2016
 * Time: 12:17 PM
 */
class ExampleMiddleware extends Slim\Middleware {

    public function __construct($debug) {
        //Define the urls that you want to exclude from Authentication, aka public urls
        $this->whiteList =['/'];
        $this->debug = $debug;
    }

    public function call()
    {
        // Get reference to application
        $app = $this->app;
        if($this->debug==0) {
            $token = $this->app->request->params('token');
            $user_id = $this->app->request->params('user_id');
            $res = UserAuth::authenticate($token);
            if ($res){
                $this->app->response->body();
            } else {
                $this->app->response->body(json_encode(['error' => 'denied']));
            }
        }
        $this->next->call();
    }
}