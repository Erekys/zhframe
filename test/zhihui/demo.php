<?php
include_once('../Class/curl.php');
include_once('../Class/phpQuery.php');
include_once("getPic.php");
include_once("analy.php");
$analy = new Analy();
$curl = new Http();
$url = "http://www.mafengwo.cn/i/3096971.html";
//$html = $curl->curlRequest($url)['response'];
$html = file_get_contents('123.log');
phpQuery::newDocumentHTML($html, 'utf8');
$title = pq("h1")->text();
foreach(pq("#pnl_contentinfo > p") as $info) {
    $img = pq($info)->find("img")->attr("data-src");  
    $tips = pq($info)->find("span a")->text();
    $value = '';
    if(pq($info)->attr('class') == "_j_note_content") {
        $value = pq($info)->text();    
    }
    $items[] = array(
        'value' => trim($value),
        'img' => $img,
        'tips' => trim($tips)
    );
}
var_dump($items);die;








$analy->enter("wenzhang20161124.json");die;
$lists = json_decode(file_get_contents("/Users/lisuxiang/Desktop/test/weixin/data/weixin/w_284_355334.json"), true);
foreach(file("/Users/lisuxiang/Desktop/test/weixin/images/weixin/weixin.txt") as $line){
    $arrLine = explode("=", $line);
    $key = $arrLine[1];
    $img = array_pop(explode("/", $arrLine[0]));
    $images[$img] = $key;
}
$values = array();
foreach($lists['content']['items'] as $temp) {
    if($temp['img']) {
        $img = explode("/",$temp['img'])[4].".".array_pop(explode("=", $temp['img']));
       $image = "http://yp.storage.youpu.cn/".str_replace("_", "/",$images[$img]); 
       $temp['img'] = $image;
       $values[] = $temp;
    } else {
        $values[] = $temp;    
    }
}
$lists['content']['items'] = $values;
error_log(json_encode($lists), '3', "w_".$lists['checkNum']."_".$lists['memberId'].'.json');die;









$analy->replaceImage();die;
$analy->getPic();die;

die;



$image = "/Users/lisuxiang/Desktop/test/Class/uploadImageToOss/weixin.txt";
$datas = array(
    array(
        '','','微信公众号','284','日本','大阪','景点,美食','355334','http://mp.weixin.qq.com/s?__biz=MzA4MTExODA0NQ==&mid=2652878778&idx=1&sn=18e29cb568db38c1b644a0ebb2196d15&mpshare=1&scene=1&srcid=1031rAiDlsmEMlaLigrfH6qL&from=singlemessage&isappinstalled=0#wechat_redirect'  
    )
);
error_log(json_encode($datas), '3', 'demo.json');die;






foreach(file($image) as $info) {
    $arrImg = explode("=", $info);
    $key = $arrImg[1]; 
    $name = array_shift(explode(".", basename($arrImg[0])));
    if($name && $key) {
        $images[$name] = $key;    
    }
}
$desc = array();
foreach($datas['items'] as $item) {
    if(strstr($item, 'wx_fmt')) {
       $arrItem = explode("/", $item);
       $pic = $images[$arrItem[4]];
       if($pic) {
            $picUrl = "http://yp.storage.youpu.cn/".str_replace("_", "/", $pic);
            $img = "<img src='".$picUrl."'";
            $desc[] = $img;
        }
    } else {
        $desc[] = trim($item);
    }
}
$datas['items'] = $desc;


var_dump(json_encode($datas));die;





ini_set('memory_limit', '1024M');

$curl = new Http();
$url = "http://www.mafengwo.cn/i/5547098.html";
//$html = $curl->curlRequest($url);
$html = file_get_contents("weixin.html");

phpQuery::newDocumentHTML($html, 'utf8');
$title = pq("#activity-name")->text();
$createTime = pq("#post-date")->text();
foreach(pq("#js_content p") as $info) {    
    if(pq($info)->attr("style")) {
        continue;    
    }

    $item = pq($info)->find('img')->attr('data-src');
    if(!$item) {
        $item = pq($info)->find('span')->text();    
    }
    $items[] = $item;
}
$datas = array(
    'title' => trim($title),
    'createTime' => $createTime,
    'items' => array_filter($items)
);
echo json_encode(array_filter($datas));die;



?>
