<?php
//define('<br>', '\n');
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
function get_thread($tid,$pn){
    global $kw;
    $data=array(
        '_client_id=wappc_1396611108603_817',
        '_client_type=2',
        '_client_version=5.7.0',
        '_phone_imei=642b43b58d21b7a5814e1fd41b08e2a6',
        'from=tieba',
        'kz='.$tid,
        'pn='.$pn,
        'q_type=2',
        'rn=30',
        'with_floor=1');
    $data=implode('&', $data).'&sign='.md5(implode('', $data).'tiebaclient!!!');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://c.tieba.baidu.com/c/f/pb/page');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','Referer:http://tieba.baidu.com/','User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0','Connection:keep-alive'));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $re = json_decode(curl_exec($ch),true); 
    curl_close($ch);
    /*这些都是记录帖子信息 昌维2015-10-04
    //$fid=$re['forum']['id'];
    $fname=$re['forum']['name'];
    //$touxiang=$re['forum']['avatar'];
    //print_r($re);
    $un=$re['post_list']['0']['author']['name'];
    $shijian=$re['post_list']['0']['time'];
    $title=$re['post_list']['0']['title'];
    $sql = "INSERT INTO tongji_threads (tid,title,author,fname,shijian) VALUES ('{$tid}','{$title}','{$un}','{$fname}','{$shijian}')";
    mysql_query($sql);//echo $sql.'<br>';echo mysql_error().'<br>';
    */
    /*
    //开始获取三十条帖子内容然后分词
    for ($i=0; $i < 30; $i++) { 
        //$un=$zuozhe[$i]=$re['post_list'][$i]['author']['name'];
        //$shijian=$shijian[$i]=$re['post_list'][$i]['time'];
        $content.=$neirong[$i]=$re['post_list'][$i]['content']['0']['text'];
    }
    */
    $content=$title.$re['post_list']['0']['content']['0']['text'];
    //var_dump($content);//return 0;
    $fencijieguo = fenci($content);//var_dump($fencijieguo);
    foreach ($fencijieguo['words'] as $key1 => $value1) {
        $k = $value1['word'];
        if ($k=='' or strlen($k)<4) {
            continue;
        }
        $sql = "INSERT INTO tongji_result (tid,kw,un,keyword) VALUES ('{$tid}','{$kw}','{$un}','{$k}')";//echo $sql.'<br>';
        mysql_query($sql);//echo mysql_error().'<br>';
    }
    //return $a = array('zuozhe' => $zuozhe,'shijian' => $shijian,'neirong' => $neirong,'fid' => $fid,'fname' => $fname,'touxiang' => $touxiang);
}
/*
//这是pullword的分词api，因为不稳定所以不用。——昌维 2015-10-03 
function fenci($needtodo){
    $data = 'source='.$needtodo.'&param1=0&param2=0';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://api.pullword.com/post.php');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $re = curl_exec($ch); 
    curl_close($ch);
    //$re = file_get_contents('http://api.pullword.com/get.php?source='.$needtodo.'&param1=0&param2=0');
    return explode("\n", trim($re));
}
*/
//改用SCWS 中文分词接口 ——昌维 2015-10-04
function fenci($needtodo){
    $data = 'data='.$needtodo.'&respond=json&duality=yes';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://www.xunsearch.com/scws/api.php');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $re = json_decode(curl_exec($ch),1); 
    curl_close($ch);//print_r($re);
    return $re;
}
//exit(fenci('我操你妈的大傻逼脑残鸡巴垃圾抗压吧死妈玩意儿'));
//print_r(fenci('我操你妈的大傻逼脑残鸡巴垃圾抗压吧死妈玩意儿'));exit();
    //$kw = '武汉船舶职业技术学院';
$tiebas = mysql_query('select kw from tongji_tieba');
mysql_query('truncate table tongji_result');
while($row = mysql_fetch_assoc($tiebas)){ 
    $kw = $row['kw'];
    $re = get_froum($kw,'1');
    $tids = $re['forum']['tids'];
    $tids = substr($tids,0,strlen($tids)-1);
    $tids = explode(',',$tids);
    foreach ($tids as $key => $value) {
        get_thread($value,'1');
    }
}
//print_r(get_froum('bug'));
    $timer->stop(); //在脚本文件结束处调用这个方法
    echo '当前脚本执行时间：<b><kbd>'.$timer->spent().'</kbd></b> 秒 code by <a href="http://tieba.baidu.com/home/main?id=61bcb2fdceac303031930d&fr=itb">昌维</a>';
?>