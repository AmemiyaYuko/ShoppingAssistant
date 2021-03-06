<?php
require("inc/header.inc");
?>
<div class="collapse navbar-collapse">
	<form class="navbar-form navbar-center" role="search" action="search.php" method="POST">
    	<div class="form-group">
        	<input type="text" class="form-control" placeholder="Search" size="36" name="info">
        </div>
    	<button type="submit" class="btn btn-info">Submit</button>
    </form>
</div>
<?php
$NUMFORPIC=3;
$MAXPAGES=8;
$NUMFORTITLE=9;
$MAXITEMS=50;
//Because Taobao has too many results...
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
		if (!(strstr($ts,"<span class=\"noResultsTitleKeyword\">")=="")){
			return 0;
		}
		$ts=fgets($tfile);

	}
	return 1;	
}
$info=trim($_POST["info"]);
if ($info==$_SESSION["last_info"]){
	$quest=($_POST["quest"]);
}
$_SESSION["last_info"]=$info;
$counter=0;
$TBOriUrl="http://s.taobao.com/search?q=".$info;			//Taobao url
$AZOriUrl="http://www.amazon.cn/s/ref=sr_pg_1";	//Amazon url

//Taobao Analysis Module
$TBfile = fopen($TBOriUrl,"r");
$page=0;
$i=0;
//promote=0&sort=sale-desc&tab=all&q=dota2&s=00#J_relative
$TBUrl=$TBOriUrl."&romote=0&sort=sale-desc&tab=all&s=".$page."#J_relative";
// URL of first page
while ((fopen($TBUrl,"r"))&&($page<$MAXPAGES)){
	$TBfile=fopen($TBUrl,"r");
	while(!feof($TBfile)) { 
		$str=fgets($TBfile); 
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
} 
//Amazon Analysis Module
$page=1;
$AZUrl=$AZOriUrl."?page=".$page."&keywords=".$info."&ie=GBK";
while ((check_for_z($AZUrl)==1)&&($page<$MAXPAGES)){
	$AZfile=fopen($AZUrl,"r");
	while(!feof($AZfile)){
		$str=fgets($AZfile);
		$kw="<div id=\"result_";
		if (!(strstr($str, $kw)=="")){
			$str=fgets($AZfile);
			$str=fgets($AZfile);
			$ItemBox_Url[$counter]=purify($str,"'",1);
			$str=fgets($AZfile);
			$ItemBox_Pic[$counter]=purify($str,'"',3);
			while (strstr($str,"class=\"lrg\">")=="")
				$str=fgets($AZfile);
			$pos=stripos($str,"<span class=\"lrg\">")+strlen("<span class=\"lrg\">");
			$str=substr($str, $pos);
			$pos=stripos($str,"</span>");
			$ItemBox_Title[$counter]=substr($str,0,$pos);
			$error=0;
			while ((strstr($str,"<span class=\"bld lrg red\"> ￥")=="")||(!(strstr($str,"</del>")=="")))
				if ($error>500) {
					break;
				}
				else{
					$str=fgets($AZfile);
					$error++;
				}
			$pos=stripos($str, 	"<span class=\"bld lrg red\"> ￥")+strlen("<span class=\"bld lrg red\"> ￥");
			$str=substr($str,$pos);
			//echo "new: ".$str;
			$pos=stripos($str, "</span>");
			$ItemBox_Price_str[$counter]=substr($str,0,$pos);
			$ItemBox_Price_float[$counter]=floatval($ItemBox_Price_str[$counter]);
			$counter=$counter+1;
		}
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
		<a href="<?php echo $ItemBox_Url[$i]?>" width="210px" height="210px"> <?php echo $ItemBox_Title[$i];?></a>
		<br>
		<a>Price : <?php echo $ItemBox_Price_str[$i];?></a>
	</div>
	<?php

}
require ("inc/footer.inc");
?>