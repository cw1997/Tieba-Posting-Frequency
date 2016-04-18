<?php
require "timer.class.php";//把类文件引用进来，根据你的实际情况来确定路径，这里是在同级目录
require "conn.php";
$timer= new Timer(); 
$timer->start(); //在脚本文件开始执行时调用这个方法
ini_set("max_execution_time", 6000);

function get_froum($kw,$pn) {
    $header = array ("Content-Type: application/x-www-form-urlencoded");
    $data=array("BDUSS=".$bduss,
        "_client_id=wappc_1396611108603_817",
        "_client_type=2",
        "_client_version=5.7.0",
        "_phone_imei=642b43b58d21b7a5814e1fd41b08e2a6",
        "from=tieba",
        "kw=".$kw,
        "pn=".$pn,
        "q_type=2",
        "rn=100",
        "with_group=1");
    $data=implode("&", $data)."&sign=".md5(implode("", $data)."tiebaclient!!!");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://c.tieba.baidu.com/c/f/frs/page");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $re = json_decode(curl_exec($ch),1); 
    curl_close($ch);
    return $re;
}

$tieba = mysql_query('select kw from tongji_tieba');

while ($tiebas = mysql_fetch_assoc($tieba)) {
    $needtieba = $tiebas['kw'];
    $tieba_info = get_froum($needtieba,'1');
    $member_num = $tieba_info['forum']['member_num'];
    $thread_num = $tieba_info['forum']['thread_num'];
    $post_num = $tieba_info['forum']['post_num'];
    $shijian = time();
    /*
    mysql_query("UPDATE tongji_tieba SET member_num = '{$member_num}' WHERE kw = '{$needtieba}'");echo mysql_error().'<br>';
    mysql_query("UPDATE tongji_tieba SET thread_num = '{$thread_num}' WHERE kw = '{$needtieba}'");echo mysql_error().'<br>';
    mysql_query("UPDATE tongji_tieba SET post_num = '{$post_num}' WHERE kw = '{$needtieba}'");echo mysql_error().'<br>';
    */
    mysql_query("UPDATE tongji_tieba SET shijian = '{$shijian}' WHERE kw = '{$needtieba}'");echo mysql_error().'<br>';
    mysql_query("INSERT INTO tongji_num (kw,member_num,thread_num,post_num,shijian) VALUES ('{$needtieba}','{$member_num}','{$thread_num}','{$post_num}','{$shijian}')");
}

    $timer->stop(); //在脚本文件结束处调用这个方法
    echo '当前脚本执行时间：<b><kbd>'.$timer->spent().'</kbd></b> 秒 code by <a href="http://tieba.baidu.com/home/main?id=61bcb2fdceac303031930d&fr=itb">昌维</a>';
?>