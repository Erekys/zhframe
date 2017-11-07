<?php
include_once('../Class/curl.php');
include_once('../Class/phpQuery.php');
include_once("getPic.php");
include_once("analy.php");
$curl = new Http();
$url = "http://mp.weixin.qq.com/profile?src=3&timestamp=1488163499&ver=1&signature=3ozxxnmhUILfG0YB1Lh8v4NwoygePqlGfGmgfvlF45sjdsQkHgtmVwZqg*vEgvRg-R5ifjEKhzrNKJNriFoe7g==";

$_url = "http://mp.weixin.qq.com";
//抓列表页链接
$html = $curl->curlRequest($url)['response'];
//$html = file_get_contents('html.html');
phpQuery::newDocumentHTML($html, 'utf8');
$title = pq(".profile_nickname")->text();
$chatNum = pq('.profile_account')->text();
$descValue = pq('.profile_desc_value:first')->text();
$company = pq('.profile_desc_value:last')->text();
$preg = "/[.*\s\S]msgList[.\s\S]\=[.\s\S](.*);[.\r\n]/";
preg_match($preg, $html, $row);
$result = $row[1];
var_dump($title);

$title = trim($title);
$chatNum = trim(array_pop(explode(' ', $chatNum)));
$arrReturn = array(
    'name' => $title,
    'chatNum' => $chatNum,
    'descValue' => $descValue,
    'company' => $company,
    'content' => json_decode($result, true),
);
error_log(json_encode($arrReturn)."\r\n", 3, 'wechatList.txt');
$list = json_decode($result, true);
foreach($list['list'] as $info) {
    $url = str_replace('&amp;', "&", $info['app_msg_ext_info']['content_url']);
    $url = $_url.$url;
    $html = $curl->curlRequest($url)['response'];
    //$html = file_get_contents('html.html');
    phpQuery::newDocumentHTML($html, 'utf8');
    $title = pq('#activity-name')->text();
    $createTime = pq('#post-date')->text();
    $author = pq('#post-user')->text();
    $preg = "/[.*\s\S]cover[.*\s\S]\=[.*\s\S]\"(.*)\"/";
    preg_match($preg, $html, $row);
    $header = $row[1];
    $content = array();
    if(count(pq("#js_content > section")) == 1) {
        $tree = pq("#js_content > section p,section");
    } else {
        $tree = pq("#js_content > p,blockquote,section");
    }
    foreach($tree as $info) {
        $value = trim(pq($info)->text());
        $img = pq($info)->find('img')->attr('data-src');
        if( $value || $img ){
            $content[] = array(
                'desc' => $value,
                'img' => $img,
            );   
        }
    }
    $_content = array();
    foreach($content as $k => $row) {
        if( !in_array($row, $_content)) {
            $_content[] = $row;
        }
    }
    $tmp = array(
        'title' => trim($title),
        'createTime' => $createTime,
        'author' => $author,
        'headerImg' => $header,
        'content' => $_content,
    );
    //解析详情页
    var_dump($url);
    //echo json_encode($tmp);
    sleep(1);
    error_log(json_encode($tmp)."\r\n", 3, 'wechatContent.txt');
}























?>
