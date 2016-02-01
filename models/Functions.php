<?php
/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 10/12/2015
 * Time: 2:45 PM
 */

class Functions extends Illuminate\Database\Eloquent\Model {

    /**
     * @param $params
     * @param $model - User,Jobs
     * @return mixed
     * Only for one table
     * eg. Select * from user where .....
     */

    public static function update_details($model,$params)
    {
     /*   foreach($params as $key=>$value)
        {
            $model->where($key,'=',$value);
        }
        return $model->get();*/


        $model->update($params);
    }





}