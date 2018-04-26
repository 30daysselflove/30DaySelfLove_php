<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adam Dougherty
 * Date: 5/22/13
 * Time: 5:56 PM
 * To change this template use File | Settings | File Templates.
 */

namespace lib;


class FTP {

    private $conn;

    public function __construct($url){
        $this->conn = ftp_connect($url);
    }

    public function __call($func,$a){
        $func = "ftp_" . $func;
        if(function_exists($func)){
            array_unshift($a,$this->conn);
            return call_user_func_array($func,$a);
        }else{
            // replace with your own error handler.
            die("$func is not a valid FTP function");
        }
    }
}