<?php
function cget($url){
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0','Connection:keep-alive','Referer:http://wapp.baidu.com/'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch,CURLOPT_COOKIE,$cookie);
    $get_url = curl_exec($ch);
    curl_close($ch);
    return $get_url;
}
header("Content-type: image/png"); 
$url=$_GET["url"];
echo cget($url,COOKIE);
?>

