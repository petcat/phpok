<?php
#============================
#	Filename: notice.qgmod.php
#	Note	: 公告模块
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-3-25
#============================
global $FS,$DB,$prefix;
$md5 = md5(LANGUAGE_ID."_".$title_length."_".$msg_length."_".$limit."_".$postdate);
$cache_file = "data/cache/notice_".$md5.".php";
$check_status = false;
if($iscache)
{
	$check_status = CheckCache($cache_file);
}
if($check_status)
{
	include_once($cache_file);
	unset($cache_file);
	return $notice;
}
$sql = "SELECT * FROM ".$prefix."notice WHERE language='".LANGUAGE_ID."' ORDER BY postdate DESC LIMIT 0,".$limit;
$notice = array();
$rslist = $DB->qgGetAll($sql);
if(!$rslist)
{
	return false;
}
foreach($rslist AS $key=>$value)
{
	$value["postdate"] = date($postdate,$value["postdate"]);
	$value["target"] = $value["url"] ? " target='_blank'" : "";
	if($value["content"])
	{
		$value["content"] = preg_replace("/<.*?>/is","",$value["content"]);
		$value["content"] = str_replace("'","",$value["content"]);
		if($value["content"] && $msg_length>0 && !$value["url"])
		{
			$value["content"] = CutString($value["content"],$msg_length,"……");
		}
	}
	else
	{
		if($value["url"])
		{
			$value["content"] = $value["url"];
		}
	}
	$value["cut_subject"] = $title_length>0 ? CutString($value["subject"]) : $value["subject"];
	$value["url"] = $value["url"] ? $value["url"] : "notice.php#".$value["id"];
	$notice[] = $value;
}
unset($rslist);
$FS->qgWrite($notice,$cache_file,"notice");
unset($cache_file);
return $notice;
?>