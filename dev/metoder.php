<?php 
    function siffror_test($str){
        return preg_match('/\d/', $str) > 0;
    };

    function inputTest($str){
        return preg_match('/\S/', $str) && preg_match('/[a-zA-Z]/', $str);
    };
?>