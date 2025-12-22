<?php
#============================
#	Filename: spelist.qgmod.php
#	Note	: 专题列表
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-3-25
#============================
global $FS,$DB,$prefix;
$md5 = md5(LANGUAGE_ID."_".$groupid."_".$length."_".$count);
$cache_file = "data/cache/spelist_".$md5.".php";
$check_status = false;
if($iscache)
{
	$check_status = CheckCache($cache_file);
}
if($check_status)
{
	include_once($cache_file);
	unset($cache_file);
	return $spelist;
}
$sql = "SELECT id,subject,style,url FROM ".$prefix."special WHERE typeid='".$groupid."' AND ifcheck='1' AND language='".LANGUAGE_ID."' LIMIT ".$count;
$rslist = $DB->qgGetAll($sql);
if(!$rslist)
{
	return false;
}
foreach($rslist AS $key=>$value)
{
	$value["cut_subject"] = $length ? CutString($value["subject"],$length,"…") : $value["subject"];
	$value["target"] = $value["url"] ? " target='_blank'" : "";
	$value["url"] = $value["url"] ? $value["url"] : "special.php?id=".$value["id"];
	$spelist[] = $value;
}
$FS->qgWrite($spelist,$cache_file,"spelist");
unset($cache_file);
return $spelist;
?>