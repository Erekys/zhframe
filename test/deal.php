<?php
include_once("curl.php");

var_dump(MD5("_yp_superjourney_line_piaotu_310311"));die;



$curl = new Http();
$head = array("User-Agent: youputrip/343", "version: 2.5.0");

$datas = json_decode( file_get_contents("20161019.json"), true);

foreach($datas as $data) {
    $id = $data['id'];
    var_dump($id);
    $url = "http://search.youpu.cn/ypdata/updateProductIndexById?id=".$id;
    $curl->curlRequest($url, '', '', $head);
}
die;





$datas = json_decode( file_get_contents("tongchengDest.json"), true);

foreach($datas as $data) {
    $place[$data[0]] = $data[3];
}

foreach($place as $k=>$_place) {
   echo "'".$k."' => '".$_place."'\n"; 
}
die;

$files = file("../haoqiao.txt");

foreach($files as $info) {
    $str = array_shift(explode("=", $info));
    $_str = array_pop( explode("/", $str));
    $array[] = array_shift( explode(".", $_str));
}
//file_put_contents('youtupiandehaoqiaojiudian.txt', implode(",", $array));die;
error_log(implode("\n", $array), '3', 'youtupiandehaoqiaojiudian.txt');die;





foreach($datas as $data) {
    $arrays[] = array(
        'hotelid' => $data['hotelId'],
        'poiid' => $data['poiId']
    );    
}
$fp = fopen("haoqiaoerror.json", 'wb');
fwrite($fp, json_encode($arrays));
fclose($fp);
var_dump($arrays);die;
die;




$fileName = "../好巧酒店排名.txt";
$handle = fopen($fileName,'r');
$handle = file($fileName);
$datas = array();
foreach($handle as $info) {
    $datas[] = explode(",", trim($info));    
    
}

$fp = fopen("haohaoqiaoranking.json", 'wb');
fwrite($fp, json_encode($datas));
fclose($fp);
die;





?>
