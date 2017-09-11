<?php

if(!function_exists('dd')){
    function dd($var){
        echo '<pre>';
        if(is_bool($var)||is_null($var)){
            var_dump($var);
        }else{
            print_r($var);
        }
    }
}
if(!function_exists('c')){
    function c($a){
        $info = explode('.',$a);
        $data = include '../system/config/'.$info[0].'.php';
        return isset($data[$info[1]])?$data[$info[1]]:null;
    }
}

?>