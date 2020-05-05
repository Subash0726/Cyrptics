<?php
/**
 * Created by PhpStorm.
 * User: azizt
 * Date: 2/22/2017
 * Time: 11:08 AM
 */

$start_timestamp=null;
$is_overtime=false;

if($_SESSION[$ses_stime_key]!=""){
    $start_timestamp=strtotime($_SESSION[$ses_stime_key]);

    $cur_timestamp=date_create();
    $cur_timestamp=strtotime (date_format($cur_timestamp,"Y-m-d H:i:s"));

    $end_timestamp=$start_timestamp+(4*60*60);

    $remain_timestamp=$end_timestamp-$cur_timestamp;
    $remain_secs=$remain_timestamp;
    if($remain_secs<=0)
        $is_overtime=true;

    $remain_hours=(int)($remain_secs/(60*60));
    $remain_secs=($remain_secs%(60*60));
    $remain_mins=(int)($remain_secs/(60));
    $remain_secs=($remain_secs%(60));
}

/*echo "Start Time: ".$start_timestamp;
echo "<br/>End Time: ".$end_timestamp;

echo "<br/>Current Time: ".$cur_timestamp;

echo "<br/>Remain Timestamp: ".$remain_timestamp;
echo "<br/>Remain Hours: ".$remain_hours;
echo "<br/>Remain Mins: ".$remain_mins;
echo "<br/>Remain Secs: ".$remain_secs;*/
