<?php
class Timer{
private $startTime = 0; //保存脚本开始执行时的时间（以微秒的形式保存）
private $stopTime = 0; //保存脚本结束执行时的时间（以微秒的形式保存）
 
//在脚本开始处调用获取脚本开始时间的微秒值
function start(){
$this->startTime = microtime(true); //将获取的时间赋值给成员属性$startTime
}
//脚本结束处嗲用脚本结束的时间微秒值
function stop(){
$this->stopTime = microtime(true); //将获取的时间赋给成员属性$stopTime
}
//返回同一脚本中两次获取时间的差值
function spent(){
//计算后4舍5入保留4位返回
return round(($this->stopTime-$this->startTime),4);
}
}
?>