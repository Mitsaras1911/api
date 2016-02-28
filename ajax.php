<?php
/**
 * Created by PhpStorm.
 * User: MITSARAS
 * Date: 2/27/2016
 * Time: 12:21
 */


$p = $_POST['data'];
foreach ($p as $key => $value){
    $q = "UPDATE user SET '$key' = '$value'";
    echo $key."=>".$value." ";
}
