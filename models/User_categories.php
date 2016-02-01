<?php
/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 10/7/2015
 * Time: 11:09 AM
 * User Categories
 */

class User_categories extends Illuminate\Database\Eloquent\Model {

    protected $table ='user_categories';



    static function change_user_categories($params){
        $r = User_categories::where('userid', $params['userid']);
        $r->update($params);
        return $r->get();
    }


}

