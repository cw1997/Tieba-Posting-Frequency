<?php 
    //贴吧发帖记录查询 code by 昌维 2015-06-28
    date_default_timezone_set("Asia/Shanghai");
    error_reporting(0);
    ini_set("max_execution_time", 60);
    require "timer.class.php";//把类文件引用进来，根据你的实际情况来确定路径，这里是在同级目录
    $timer= new Timer(); 
    $timer->start(); //在脚本文件开始执行时调用这个方法
    require 'conn.php';
    $tieba = mysql_query("select kw from tongji_tieba");
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
            "rn=1",
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

    while ($tiebas = mysql_fetch_assoc($tieba)) {
        $needtieba = $tiebas['kw'];
        $zongong = mysql_num_rows(mysql_query("select * from tongji_num where kw = '{$needtieba}'"))-25;
        $result = mysql_query("select * from tongji_num where kw = '{$needtieba}' limit {$zongong},25");
        $j=0;
        $r = mysql_fetch_assoc($result);
        $lastnum = $r['post_num'];//echo $lastnum.' $lastnum<br>';
        while ($r = mysql_fetch_assoc($result)) {
            $ca = intval($r['post_num']) - intval($lastnum);//echo $ca.' $ca<br>';
            $lastnum = $r['post_num'];//echo $lastnum.' $lastnum<br>';
            $$needtieba .= $ca.',';//echo $$needtieba.' $needtoecho<br><br>';
            $shijianshuchu .= "'".date("m-d H时", $r['shijian'])."'".',';
        }
        $tibiaoshuchu .= "{
            name: '$needtieba',
            data: [
            ".$$needtieba."
            ]
        },";
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>百度贴吧统计平台 code by 昌维</title>
<!-- author:昌维 -->
    <script src="http://lib.sinaapp.com/js/jquery/1.9.1/jquery-1.9.1.min.js"></script>
    <!--<script type="text/javascript" src="http://static.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>-->
    <script type="text/javascript" src="http://static.hcharts.cn/highcharts/highcharts.js"></script>
    <script type="text/javascript" src="http://static.hcharts.cn/highcharts/modules/exporting.js"></script>
    <script type="text/javascript" src="http://static.hcharts.cn/highcharts/modules/data.js"></script>

    <script type="text/javascript" src="http://static.hcharts.cn/code/js/common.js"></script>
    <script type="text/javascript" src="http://static.hcharts.cn/code/js/codemirror.js"></script>
    <script type="text/javascript" src="http://static.hcharts.cn/code/js/javascript.js"></script>
    <script type="text/javascript" src="http://static.hcharts.cn/code/js/xml.js"></script>
    <script type="text/javascript" src="http://static.hcharts.cn/code/js/css.js"></script>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <base target=”_blank”>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>

    <style type="text/css">
        td{white-space:nowrap;}
        body{background: url('') }
    </style>

    <script type="text/javascript">
        $(function () {
            $('#sulv').highcharts({
                title: {
                    text: '百度贴吧发帖速率统计',
                    x: -20 //center
                },
                subtitle: {
                    text: 'Source: <a href="http://t.changwei.me">昌维的贴吧工具箱 t.changwei.me</a>',
                    x: -20
                },
                xAxis: {
                    categories: [<?php echo $shijianshuchu ?>]
                },
                yAxis: {
                    title: {
                        text: '速率（贴 / 小时）'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: ' 贴 / 小时'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [<?php echo $tibiaoshuchu ?>],
                credits:{
                    // enabled:true,                    // 默认值，如果想去掉版权信息，设置为false即可
                    text:'www.changwei.me',               // 显示的文字
                    href:'http://www.changwei.me',   // 链接地址
                }
            });
        });
    </script>

</head>

<body>
<!--<div id="Layer1" style="position:absolute; width:100%; height:100%; z-index:-1">    
<img src="bg2.jpg" height="100%" width="100%"/>
</div>-->
<div class="container">
<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php"><b>百度贴吧统计平台</b></a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">监控状态</a></li>
                <li><a href="http://www.changwei.me">昌维的博客</a></li>
                <li><a href="http://t.changwei.me">贴吧工具箱</a></li>
                <li><a href="submit.php">提交监控申请</a></li>
                <li><a href="about.html">关于本站</a></li>
            </ul>
        </div>
        </div>
    </nav>

    <div class="row">
        <form>
            <div class="col-xs-6 col-xs-offset-2">
                <input type="text" name="kw" class="form-control input-lg" placeholder="请输入目标贴吧">
            </div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-info btn-lg" name="submit" formaction="s.php"><span class="glyphicon glyphicon-search"></span> 立即搜索</button>
            </div>
        </form>
    </div>
    <hr>
    <div id="sulv" style="min-width: 600px; height: 400px; margin: 0 auto"></div>
    <hr>
    <div class="row">
        <?php 
        $tieba = mysql_query("select kw,shijian from tongji_tieba");
        while ($tiebas = mysql_fetch_assoc($tieba)) {
            $needtieba = $tiebas['kw'];//exit($tiebas['shijian']);
            $$needtieba = mysql_query("select tid, un, kw, count(keyword) as count, keyword from tongji_result WHERE kw = '{$needtieba}' group by keyword ORDER BY count(keyword) DESC limit 20;");
            $tieba_info = get_froum($needtieba,'1');
        ?>
        <script type="text/javascript">
            $(function () {
                $('#<?php echo 'a'.$i ?>').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '百度贴吧-<?php echo $needtieba ?>吧 关键词出现频数统计'
                    },
                    subtitle: {
                        text: 'Source: <a href="http://t.changwei.me">昌维的贴吧工具箱 t.changwei.me</a>'
                    },
                    xAxis: {
                        type: 'category',
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '16px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        },title: {
                            text: '关键词'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '出现频数（次）'
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    series: [{
                        name: '出现频数（次）',
                        data: [
                        <?php while($row = mysql_fetch_assoc($$needtieba)){ ?>
                            ['<?php echo $row['keyword'] ?>', <?php echo $row['count'] ?>],
                        <?php } ?>
                        ],
                        dataLabels: {
                            enabled: true,
                            rotation: 0,
                            color: '#FFFFFF',
                            align: 'right',
                            format: '{point.y}', // one decimal
                            y: 10, // 10 pixels down from the top
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    }],
                    credits:{
                        // enabled:true,                    // 默认值，如果想去掉版权信息，设置为false即可
                        text:'www.changwei.me',               // 显示的文字
                        href:'http://www.changwei.me',   // 链接地址
                    }
                });
            });
        </script>
        <div class="panel panel-primary table-responsive">
            <div class="panel-heading">
                <?php echo $needtieba ?>吧
            </div>
            <div class="panel-body">
                <div class="col-xs-3">
                    <br>
                    <a href="http://tieba.baidu.com/f?kw=<?php echo $needtieba ?>">
                        <div style="text-align:center">
                            <img src="touxiang.php?url=<?php echo $tieba_info['forum']['avatar'];?>">
                        </div>
                        <h2><p style="text-align:center"><?php echo $needtieba ?></p> <small><?php echo $tieba_info['forum']['slogan'] ?></small></h2>
                    </a>
                    
                    <!-- <h4>fid</h4>
                    <h4>post_num</h4>
                    <h4>thread_num</h4>
                    <h4>member_num</h4> -->
                    <!--
                    <h4>
                        <dl class="dl-horizontal">
                            <dt>fid</dt>
                            <dd>15615</dd>
                            <dt>post_num</dt>
                            <dd>15615</dd>
                            <dt>thread_num</dt>
                            <dd>15615</dd>
                            <dt>member_num</dt>
                            <dd>15615</dd>
                            <dt>first_class</dt>
                            <dd>15615</dd>
                            <dt>second_class</dt>
                            <dd>15615</dd>
                        </dl>
                    </h4>
                    -->
                </div>
                <div class="col-xs-1" style="margin-right:-50px">
                    <table style="height:300px;border-color:cccccc;border-left-style:solid;border-width:1px"><tr><td valign="top"></td></tr></table>
                </div>
                <div class="col-xs-8">
                    <div id="<?php echo 'a'.$i ?>" style="min-width: 600px; height: 300px; margin: 0 auto"></div>
                </div>
            </div>
            <div class="panel-footer">数据更新时间：<code><?php echo date('Y-m-d H:i:s',$tiebas['shijian']);?></code> 下次更新时间：<code><?php echo date('Y-m-d H:i:s',$tiebas['shijian']+3600) ?></code> 会员总数：<code><?php echo $tieba_info['forum']['member_num'];?></code> 主题帖总数：<code><?php echo $tieba_info['forum']['thread_num'];?></code> 回帖总数：<code><?php echo $tieba_info['forum']['post_num'];?></code></div>
        </div>
        <?php
        $i++;
         } ?>
    </div>
</div>

<?php     $timer->stop(); //在脚本文件结束处调用这个方法
    echo '脚本执行时间：<b><kbd>'.$timer->spent().'</kbd></b> 秒 code by <a href="http://tieba.baidu.com/home/main?id=61bcb2fdceac303031930d&fr=itb">昌维</a>'; ?>
</body>
</html>
