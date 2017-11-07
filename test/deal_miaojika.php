<?php
include_once("Class/curl.php");
$curl = new Http();

$lists = json_decode( file_get_contents("miaojika.json"), true);
foreach($lists as $list) {
    $poi = $list[0];
    $url = $list[7];   
    $str = $curl->curlRequest($url)['response'];
    if($str) {
        $name = "miaojika/".$poi.".jpg";
        $fp = fopen($name, 'wb');
        fwrite($fp, $str);
        fclose($fp);
    }
    var_dump($poi);
}
die;
















?>
