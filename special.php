<?php
#[专题管理]
require_once("global.php");
$id = intval($id);
if(!$id)
{
	qgheader("home.php");
}
$rs = $DB->qgGetOne("SELECT id,subject,content FROM ".$prefix."special WHERE language='".LANGUAGE_ID."' AND id='".$id."' AND ifcheck='1'");
if(!$rs)
{
	qgheader("home.php");
}

#[显示分类]
$rslist = $DB->qgGetAll("SELECT id,subject,style,url FROM ".$prefix."special WHERE language='".LANGUAGE_ID."' AND ifcheck='1' ORDER BY taxis ASC,id DESC");
if(!$rslist)
{
	$catelist = array();
}
else
{
	foreach($rslist AS $key=>$value)
	{
		$value["target"] = $value["url"] ? " target='_blank'" : "";
		$value["url"] = $value["url"] ? $value["url"] : "special.php?id=".$value["id"];
		$catelist[] = $value;
	}
}

$subject = $rs["subject"];
$content = $rs["content"];

$left_catelist = $catelist;
#[标题头]
$sitetitle = $subject." - ".$system["sitename"];
#[导航栏]
$lead_menu[0]["url"] = "special.php?id=".$id;
$lead_menu[0]["name"] = $subject;

if($rs["url"])
{
	$content = "<a href='".$rs["url"]."' target='_blank'>".$rs["url"]."</a>";
	$content .= "<script type='text/javascript'>window.open('".$rs["url"]."');</script>";
}

#[将内容分页]
$pageid = $pageid ? intval($pageid) : 0;
$qg_array = ContentPageArray($content,"special.php?id=".$id,$pageid);
$content = $qg_array[0];
$pagelist = $qg_array[1];

HEAD();
FOOT("special");
?>