<?php
//require("inc/header.inc");
$NUMFORPIC=3;
$NUMFORTITLE=9;
$MAXITEMS=20;
$NUMFORURL=5;
function purify($ori,$key,$num){
	$pieces=strtok($ori,'"');
	$fi=0;
	while (($pieces!=false)&&($fi<$num)){
		$fi=$fi+1;
		$pieces=strtok('"');
	}
	return $pieces;
}
function check_for_z($url){
	if (fopen($url,"r")==false) return 0;
	$tfile=fopen($url,"r");
	$ts=fgets($tfile);
	while (!feof($tfile)){
		if (!(strstr($ts,"没有找到任何与")=="")){
			return 0;
		}
		$ts=fgets($tfile);

	}
	echo $url;
	return 1;	
}
$info=trim($_POST["info"]);
$info="filco";
if ($info==$_SESSION["last_info"]){
	$quest=($_POST["quest"]);
}
$_SESSION["last_info"]=$info;
$counter=0;
$TBOriUrl="http://s.taobao.com/search?q=".$info;			//Taobao url
$AZOriUrl="http://www.amazon.cn/s/ref=sr_pg_1";	//Amazon url
/*
//Taobao Analysis Module
$TBfile = fopen($TBOriUrl,"r");
$page=0;
$i=0;
//promote=0&sort=sale-desc&tab=all&q=dota2&s=00#J_relative
$TBUrl=$TBOriUrl."&romote=0&sort=sale-desc&tab=all&s=".$page."#J_relative";
// URL of first page
while (fopen($TBUrl,"r")){
	$TBfile=fopen($TBUrl,"r");
	while(!feof($TBfile)) { 
		$str= fgets($TBfile); 
		$kw="<div class=\"row item";
		if (!(strstr($str,$kw)=="")){
			//get to the img line.
			for ($i=0;$i<13;$i++)
				$str=fgets($TBfile);
			//Add pic to ItemBox_pic
			$ItemBox_Pic[$counter]=purify($str,'"',$NUMFORPIC);

			//get to info line
			for ($i=0;$i<12;$i++)
				$str=fgets($TBfile);
			$ItemBox_Title[$counter]=purify($str,'"',$NUMFORTITLE);
			$ItemBox_Url[$counter]=purify($str,'"',$NUMFORURL);
			$price="<div class=\"price\">";
			while (strstr($str,$price)==""){
				$str=fgets($TBfile);
			}
			$p=substr($str,stripos($str,$price)+strlen($price)+2,stripos($str,"em")-stripos($str,$price)-strlen($price)-3);
			$ItemBox_Price_str[$counter]=$p;
			$ItemBox_Price_float[$counter]=floatval($p);
			$counter++;
		}
	}
	if ($page<$MAXITEMS) {$page=$page+40;} else break;
	$TBUrl=$TBOriUrl."&romote=0&sort=sale-desc&tab=all&s=".$page."#J_relative";
} */
//Amazon Analysis Module
$page=1;
$AZUrl=$AZOriUrl."?page=".$page."&keywords=".$info."&ie=GBK";
while (check_for_z($AZUrl)==1){
	$AZfile=fopen($AZUrl,"r");
	while(!feof($AZfile)){
		$str=fgets($AZfile);
		$kw="<div id=\"result_";


	}
	$page=$page+1;
	$AZUrl=$AZOriUrl."?page=".$page."&keywords=".$info."&ie=GBK";
}
$_SESSION["last_info"]=$info;
$_SESSION["item_counter"]=$counter;
$_SESSION["pic"]=$ItemBox_Pic;
$_SESSION["url"]=$ItemBox_Url;
$_SESSION["title"]=$ItemBox_Title;
$_SESSION["price_float"]=$ItemBox_Price_float;
$_SESSION["price_str"]=$ItemBox_Price_str;
// Finish analyzing
echo "<div>";
for ($i=0;$i<$counter;$i++){
	if ($i%4==0){
		?>
		</div>
		<div class="row">
		<?php
	}
	?>
	<div class="col-md-3">
		<img src="<?php echo $ItemBox_Pic[$i];?>" width="210px" height="210px">
		<br>
		<a href="<?php echo $ItemBox_Url[$i]?>"><?php echo $ItemBox_Title[$i];?></a>
		<br>
		<a>Price : <?php echo $ItemBox_Price_str[$i];?></a>
	</div>
	<?php

}
//require ("inc/footer.inc");
?>