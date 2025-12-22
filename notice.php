<?php
#[公告信息]
require_once("global.php");
$rs = $DB->qgGetAll("SELECT * FROM ".$prefix."notice WHERE language='".LANGUAGE_ID."' ORDER BY postdate DESC LIMIT 0,100");
$list = array();
foreach($rs AS $key=>$value)
{
	$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);
	if($value["url"])
	{
		$value["content"] = "<a href='".$value["url"]."' target='_blank'>".$value["url"]."</a>";
	}
	$list[] = $value;
}

#[如果公告内容为空]
if(count($list) < 1)
{
	Error($langs["notice_empty"],$siteurl."home.php");
}

#[标题头]
$sitetitle = $langs["notice_title"]." - ".$system["sitename"];
#[向导栏]
$lead_menu[0]["url"] = $siteurl."register.php";
$lead_menu[0]["name"] = $langs["notice_title"];

HEAD();
FOOT("notice");
?>