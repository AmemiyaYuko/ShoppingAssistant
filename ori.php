<?php
//require("inc/header.inc");
function purify($ori,$key){
	$pieces=strtok($ori,'"');
	while ($pieces!=false){
	echo "$pieces<br>";
		$pieces=strtok('"');
	}
}
echo "<!DOCTYPE HTML>";
$info=trim($_POST["info"]);
$info="dota2";
$TBurl="http://s.taobao.com/search?q=".$info;			//Taobao url
$AZurl="http://www.amazon.cn/s/field-keywords=".$info;	//Amazon url
$TBfile = fopen($TBurl,"r");
$i=0;
while(!feof($TBfile)) { 
	$str= fgets($TBfile); 
	echo $str;
	$kw="<div class=\"row item";
	//echo $kw;
} 
?>
