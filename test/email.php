<?php
include_once('Class/curl.php');
include_once('Class/phpQuery.php');
$curl = new Http();
for($i=37555; $i>30000; $i++) {
$url = "http://foreigner.esldewey.com.tw/jobdetail.php?Detail_ID=".$i;

sleep(1);
//抓列表页链接
$html = $curl->curlRequest($url)['response'];
//error_log($html, 3, 'html.html');die;
//$html = file_get_contents('html.html');
phpQuery::newDocumentHTML($html, 'utf8');
foreach(pq("table:eq(11) td") as $info) {
    var_dump(pq($info)->text());    
}

}



?>
