<?php

/*
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 2/1/2016
 * Time: 12:17 PM
 */
class ExampleMiddleware extends Slim\Middleware {

    public function __construct($whitelist) {
        //Define the urls that you want to exclude from Authentication, aka public urls
        $this->whiteList =$whitelist;
    }

    /**
     * User Athentication
     * Client requests with token.
     * Verify if exists
     * Change his token
     * Response the new token
     */

    public function call(){

        $path = $this->app->request()->getPath();
        if(in_array($path, $this->whiteList)) {
            //Not to be authenticated
            $this->next->call();
        } else{
            //Authenticate user
            $token = $this->app->request->params('token');
            $result = User::authenticate($token);
            if ($result) {
                //Everything ok, call next middleware
                $this->next->call();


            } else{
                //Handle Error
                $this->app->response->body(json_encode(['error'=>'denied']));
            }
        }
    }

}