<?php
include_once('Class/curl.php');
include_once('Class/phpQuery.php');
include_once("getPic.php");
include_once("analy.php");
$analy = new Analy();



$analy->enter("20170214.json");die;
$analy->getPic();die;
$analy->replaceImage();die;




?>
