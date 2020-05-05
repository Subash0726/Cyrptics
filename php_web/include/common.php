<?php
/**
 * Created by PhpStorm.
 * User: azizt
 * Date: 2/19/2017
 * Time: 1:06 PM
 */
date_default_timezone_set('Asia/Kolkata');

$ses_uid_key  = "SCTFUID";
$ses_uname_key  = "SCTFUNAME";
$ses_stime_key  = "SCTFSTIME";
$ctf_title_addon="Capture The Flag";

function ses_update_stime($stime){
    global $ses_stime_key;
    $_SESSION[$ses_stime_key]=$stime;
}

function ses_sign_in($uid,$uname,$stime){
    global $ses_uid_key;
    global $ses_uname_key;
    global $ses_stime_key;
    $_SESSION[$ses_uid_key]=$uid;
    $_SESSION[$ses_uname_key]=$uname;
    $_SESSION[$ses_stime_key]=$stime;
}

function ses_sign_out(){
    global $ses_uid_key;
    global $ses_uname_key;
    global $ses_stime_key;
    unset($_SESSION[$ses_uid_key]);
    unset($_SESSION[$ses_uname_key]);
    unset($_SESSION[$ses_stime_key]);
}

function validateText($str)
{
    $s=trim($str);
    $s=stripslashes($s);
    $s=htmlspecialchars($s);
    $s=filter_var($s, FILTER_SANITIZE_STRING);
    return $s;
}

function validateInteger($int)
{
    if (filter_var($int, FILTER_VALIDATE_INT) === 0 || !filter_var($int, FILTER_VALIDATE_INT) === false) {
        return $int;
    } else {
        $resarray=array();
        $resarray["success"]=0;
        $resarray["message"]="Numeric Value Expected : ".$int;
        echo json_encode($resarray);
        die();
    }
}

function validateBool($bool)
{
    if (filter_var($bool, FILTER_VALIDATE_INT) === 0 || !filter_var($bool, FILTER_VALIDATE_INT) === false)
    {
        //echo "It's an Integer. Bool value: ".$bool."<br/>";
        if($bool == 0 || $bool == 1)
        {
            return $bool;
        }
    }

    $resarray=array();
    $resarray["success"]=0;
    $resarray["message"]="Invalid Bool Value Found";
    echo json_encode($resarray);
    die();

}

function validateFloat($f)
{
    $checked=($f == (string)(float)$f);

    if($checked)
    {
        return $f;
    }
    else
    {
        $resarray=array();
        $resarray["success"]=0;
        $resarray["message"]="Float value Expected";
        echo json_encode($resarray);
        die();
    }
}

function validateEmail($str)
{
    $str=validateText($str);
    $str = filter_var($str, FILTER_SANITIZE_EMAIL);
    if (!filter_var($str, FILTER_VALIDATE_EMAIL))
    {
        $resarray=array();
        $resarray["success"]=0;
        $resarray["message"]="INVALID_EMAIL";
        echo json_encode($resarray);
        die();
    }

    return $str;
}

function validateURL($str)
{
    $str=validateText($str);
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$str))
    {
        $resarray=array();
        $resarray["success"]=0;
        $resarray["message"]="Invalid URL";
        echo json_encode($resarray);
        die();
    }

    return $str;
}

function parseDate($sdate)
{
    $d=strtotime($sdate);
    $d=date('Y-m-d',$d);

    return $d;
}

function parseTime($stime)
{

}

function setCustomError()
{
    set_error_handler("customError");
}

function customError($errno,$errstr,$errfile,$errline)
{
    $resarray=array();
    $resarray["success"]=0;
    $resarray["message"]=$errstr." in file: ".$errfile." on line: ".$errline;
    echo json_encode($resarray);
    die();
}