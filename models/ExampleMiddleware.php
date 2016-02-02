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
        $res = UserAuth::authenticate($token);
        if ($res){//Found User
            //Generate new token
            $this->next->call();
        }
        else
        $this->app->response->body(json_encode(['error'=>'denied']));
    }
}