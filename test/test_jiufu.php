<?php

include_once("./Class/curl.php");
$curl = new Http();
$privateNum = "888000000000168";
$privateKey = "aff167ff067e4dbe999d37af0bb848f6";
$orderUrl = "http://weixin.shancard.cn/portal/ips/index";
$queryUrl = "http://pay.shancard.cn/pay/mercCsm/charges/mercQueryOrder";

$postParam = array(
    'charset' => 'UTF-8',
    'mercId' => '888000000000041',
    'interfaceName' => 'mercCreateH5Order',
    'version' => '1.0',
    'signType' => 'MD5',
    'mercOrderNo' => '20141213134440',
    'amount' => '1',
    'validTime' => '1d',
    'description' => '毛里求斯西海岸(玛拉迪瓦)5晚7日自由行',
    'userName' => '基努里维斯',
    'userAddr' => '竞园艺术中心',
    'userMbl' => '13888888888',
    'pageUrl' => 'www.baidu.com',
    'notifyUrl' => 'www.youpu.cn',
    'hmac' => $hmac
);
$hmac = MD5( 
        $postParam['charset'] .
        $postParam['mercId']  .
        $postParam['interfaceName'] .
        $postParam['version'] .
        $postParam['signType'].
        $postParam['mercOrderNo'].
        $postParam['amount']  .
        $postParam['validTime']  .
        $postParam['description'].
        $postParam['pageUrl'] .
        $postParam['notifyUrl'] .
        $privateKey);

$postParam['hmac'] = $hmac;
//$strParam = http_build_query($postParam);
$result = $curl->curlRequest($orderUrl, $postParam);
var_dump($result);die;











?>

