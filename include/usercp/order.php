<?php
#[订单列表]
$psize = 30;#[每页30个订单]
$pageid = intval($pageid);
$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
$pageurl = "my.php?file=order";
$rscount = $DB->qgCount("SELECT id FROM ".$prefix."order WHERE userid='".$_SESSION["qg_sys_user"]["id"]."'");
$pagelist = page($pageurl,$rscount,$psize,$pageid);#[获取分页的数组]
$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."order WHERE userid='".$_SESSION["qg_sys_user"]["id"]."' ORDER BY postdate DESC LIMIT ".$offset.",".$psize);
$orderlist = array();
foreach($rslist AS $key=>$value)
{
	$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);
	$orderlist[] = $value;
}
unset($rslist);

	#[标题头]
	$sitetitle = $langs["my_order"]." - ".$langs["my_usercp"]." - ".$system["sitename"];
	#[向导栏]
	$lead_menu[0]["url"] = "my.php?file=usercp";
	$lead_menu[0]["name"] = $langs["my_usercp"];
	$lead_menu[1]["url"] = "my.php?file=order";
	$lead_menu[1]["name"] = $langs["my_order"];
HEAD();
FOOT("my_order");
?>