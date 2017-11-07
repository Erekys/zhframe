<?php
include_once('curl.php');
include_once('Class/phpQuery.php');
ini_set('memory_limit', '1024M');
$curl = new Http();
$url = "http://ebkapi.17u.cn/ivacation/Line/Query/GetAbroadLineList";






$header = array(
            "Content-type: application/json;charset=UTF-8",
            "Accept: application/json",
            "Cache-Control: no-cache", 
            "Pragma: no-cache");

$url = "http://pre4.itrip.com/api_open/product/batch_query_json";
$url = "http://pre4.itrip.com/api_open/product/price";
$time = time();
$token = MD5('itrip_api_open'.$time.'itrip123456');
$arrParam = array(
    'appKey' => 'itrip_api_open',
    'ts' => $time,
    'token' => $token,
    'productId' => 2837,
    'tripDate' => '2016-11-18 00:00:00'
);
$param = http_build_query($arrParam);
$result = $curl->curlRequest($url.'?'.$param, $arrParam, '', $header)['response'];
var_dump($result);die;

/*
$header = array(
    "_guid:70cf01f7-9022-31c4-b5b4-d72c7f496757", "PHPSESSID:90e543f83e5599632fee87466a48cd2e"
);
*/

//$html = $http->curlRequest($url)['response'];
//var_dump($html);die;
/*
$url = "http://ask.qyer.com/index.php";
$param = array(
    'action' => 'ajaxmorecomment',
    'aid' => '2116579',
    'page' => 1,
    'comment_pagesize' => 2,
    'comment_order' => 2,
    'answer_order=1' => 1,
    'dt' => ''
);
$html = $http->curlRequest($url, $param)['response'];
$html = json_decode($html, true)['data']['showstr'];
var_dump($html);die;
*/
$html = file_get_contents("123.log");

phpQuery::newDocumentHTML($html);

foreach(pq("._j_open_mdd_item a") as $info) {    
    $datas[] = array(
        'id' => pq($info)->attr("data-mddid"),
        'title' => pq($info)->attr("data-mddname"),
        'num' => pq($info)->find("em")->text()  
    );
}
$fp = fopen("mafengwo.json", 'wb');
fwrite($fp, json_encode($datas));
fclose($fp);
die;










foreach(pq(".ask_tag_strong") as $tab) {
    $tags[] = pq($tab)->text();    
}
$question =  pq(".ask_detail_content_title")->text();
$content = pq(".ask_detail_content_text")->text();
$answerCount = pq(".ask_detail_item .clearfix span.fl")->text();
//占位  关注数目
$focusNum = pq(".ask_detail_content .fb")->text();
$reportId = pq(".ask_detail_content .ui_headPort")->attr('alt');
$reportNick = pq(".ask_detail_content img")->attr('alt');
$reportTime = ''; 

foreach(pq(".jsanswerbox") as $box) {
    $an_id = pq($box)->find(".useful_left")->attr("value");
    $an_nick = pq($box)->find(".ui_headPort_img")->attr("alt");
    $an_id = pq($box)->find(".ui_headPort")->attr("alt");
    $an_time = pq($box)->find(".normal_text")->text();
    $an_count = pq($box)->find(".upvote-count")->text();
    $an_countent = pq($box)->find(".mod_discuss_box_text")->text();
    /*
    foreach(pq($box)->find("li") as $li) {
        $rep_id = pq($li)->find(".ui_headPort")->attr("alt");    
        $rep_nick = pq($li)->find(".mod_discuss_reply_box_name a")->text();    
        $rep_time = pq($li)->find(".mod_discuss_reply_box_name")->text();    
        $rep_content = pq($li)->find(".mod_discuss_reply_box_text")->text();    
        $rep[] = array(
            'rep_id' => $rep_id,
            'rep_nick' => $rep_nick,
            'rep_time' => $rep_time,
            'rep_content' => $rep_content
        );
        var_dump($rep);die;
    }
    */
    $answers[] = array(
        'an_id' => $an_id,
        'an_nick' => $an_nick,
        'an_id' => $an_id,
        'an_time' => $an_time,
        'an_count' => $an_count,
        'an_countent' => $an_countent,
        //'rep' => $rep
    );
}
$result = array(
    'tags' => $tags,
    'question' => $question,
    'content' => $content,
    'answerCount' => $answerCount,
    'focusNum' => $focusNum,
    'reportId' => $reportId,
    'reportNick' => $reportNick,
    'reportTime' => $reportTime,
    'answers' => $answers
);

var_dump($result);die;



























$url = "http://rails.ctrip.com/international/Ajax/PTPProductListHandler.ashx?Action=GetPTPProductList";
$url = "http://m.ctrip.com/restapi/soa2/10487/json/GetPTPProductList";
$url = "http://m.ctrip.com/restapi/soa2/10487/json/PassProductRecommend";
$post = '{
    "FromCity": "FRNCE",
        "ToCity": "FRPAR",
        "DepartureDate": "2016-07-22",
        "Adults": 1,
        "Children": 0,
        "Youth": 0,
        "Seniors": 0,
        "SourceType": 1,
        "head": {
            "cid": "12001085710021495554",
            "ctok": "",
            "cver": "618.000",
            "lang": "01",
            "sid": "8890",
            "syscode": "12",
            "auth": null,
            "extension": [{
                "name": "protocal",
                "value": "file"
            }]
        },
        "contentType": "json"
}'; 

$data = $http->curlRequest($url, $post);
var_dump($data);die;














$pox = "";
use QL\QueryList;


$html = file_get_contents("123.log");
phpQuery::newDocumentHTML($html);
$name = pq(".poi-name .name")->text();
$enName = pq(".poi-name .smaller-font-name")->text();
$score = pq(".header-score-details-left-score")->text();

$baseInfo = array();
foreach(pq(".header-poi-base-info > div") as $info) {
    $baseInfo[] = trim(pq($info)->text());    
}
$num = pq(".header-bookmark-count")->text();
$judge = pq(".header-smile-section")->text();
$address = pq(".address-info-section .content")->text();

$phone =  pq(".telephone-section .content")->text();

$intro = pq(".introduction-section .content")->text();

$imgs = array();

foreach(pq(".dropdown-section-content-container a") as $info) {
    $img = "http://tw.openrice.com".pq($info)->attr("href");
    $title = trim(pq($info)->text());
    $imgs[$img] = $title;

}
$data = array(
        'name_cn' => $name,
        'name_en' => $enName,
        'score' => trim($score),
        'baseInfo' => $baseInfo,
        'judge' => trim($judge),
        'num' => $num,
        'address' => trim($address),
        'phone' => $phone,
        'intro' => trim($intro),
        'images' => $imgs
        );



















foreach($urls as $url) {
    $arrUrl = explode("/", $url);
    $code = urlencode(array_pop($arrUrl));
    $_url = implode("/", $arrUrl);
    $result = array();
    $html = $http->curlRequest($_url."/".$code)['response'];
    phpQuery::newDocumentHTML($html);
    foreach( pq("#or-route-sr1-filters-lamdmark-district-0 .btn") as $info) {
        $result[] = trim(pq($info)->text());     
    } 
    array_shift($result);

    foreach($result as $val) {
        $rows[] = $_url."/".urlencode($val);   
    }
}
var_dump(count($rows));

$fp = fopen("openrice/openrice_place_urls.json", 'wb');
fwrite($fp, json_encode($rows));
fclose($fp);die;















?>
