<?php
include_once('Class/curl.php');
include_once('Class/phpQuery.php');
ini_set('memory_limit', '1024M');

$curl = new Http();
$header = array(
    ''
);
//$pox = "171.92.4.67:9000";
$pox = '';

$url = "http://fendou.itcast.cn/article/updatevote?dataid=394";
$url = "http://foreigner.esldewey.com.tw/jobdetail.php?Detail_ID=37554";
$str = $curl->curlRequest($url, '', '', [], $pox);
var_dump($str);die;
error_log($str['response'], 3, '123.html');die;

?>
