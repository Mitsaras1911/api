<?php

/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 2/1/2016
 * Time: 12:17 PM
 */
class ExampleMiddleware extends Slim\Middleware
{

    /**
     * Call
     *
     * Perform actions specific to this middleware and optionally
     * call the next downstream middleware.
     */
    public function call(){
        //$key = $this->app->request()->getResourceUri();
        //$rsp = $this->app->response();
        $token = $this->app->request->params('token');
        $user_id = $this->app->request->params('user_id');
        $res = UserAuth::authenticate($token, $user_id);
        if ($res){
            // cache miss... continue on to generate the page
            $this->next->call();
        }
        else
        $this->app->response->body(\MongoDB\BSON\toJSON('error'));
    }
}