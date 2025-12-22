<?php
#============================
#	Filename: special.qgmod.php
#	Note	: 专题内容模块
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-3-25
#============================
global $FS,$DB,$prefix;
$md5 = md5(LANGUAGE_ID."_".$special_id."_".$special_length);
$cache_file = "data/cache/special_".$md5.".php";
$check_status = false;
if($iscache)
{
	$check_status = CheckCache($cache_file);
}
if($check_status)
{
	include_once($cache_file);
	return $special;
}
$sql = "SELECT * FROM ".$prefix."special WHERE id='".$special_id."' AND ifcheck='1'";
$special = $DB->qgGetOne($sql);
if(!$special)
{
	return false;
}
foreach($special AS $key=>$value)
{
	$m = $key."_length";
	if($array[$m])
	{
		$value = preg_replace("/<.*?>/is","",$value);
		$value = str_replace("'","",$value);
		$n = "cut_".$key;
		$special[$n] = CutString($value,$array[$m],"…");
	}
}
if($special_length && $special["content"])
{
	$special["content"] = preg_replace("/<.*?>/is","",$special["content"]);
	$special["content"] = str_replace("'","",$special["content"]);
	$special["content"] = CutString($special["content"],$special_length,"…");
}
else
{
	$special["content"] = str_replace("'","",$special["content"]);
}
$special["target"] = $special["url"] ? " target='_blank'" : "";
$special["url"] = $special["url"] ? $special["url"] : "special.php?id=".$special_id;
#[将数据写入缓存]
$FS->qgWrite($special,$cache_file,"special");
unset($cache_file);
return $special;
?>