<?php
#============================
#	Filename: soncate.qgmod.php
#	Note	: 根据当前的分类ID得到下一级的分类ID
#	Version : 2.2
#	Author  : qinggan
#	Update  : 2008-5-11
#============================
global $FS,$DB,$prefix;
if(!$cateid)
{
	return false;
}
$md5 = md5(LANGUAGE_ID."_".$cateid);
$cache_file = "data/cache/soncate_".$md5.".php";
$check_status = false;
if($iscache)
{
	$check_status = CheckCache($cache_file);
}
if($check_status)
{
	include_once($cache_file);
	unset($cache_file);
	return $catelist;
}
$sql = "SELECT id,catename,catestyle FROM ".$prefix."category WHERE parentid='".$cateid."' AND status='1' AND language='".LANGUAGE_ID."' ORDER BY taxis ASC,id DESC";
$catelist = $DB->qgGetAll($sql);
if(!$catelist)
{
	return false;
}
$FS->qgWrite($catelist,$cache_file,"catelist");
unset($cache_file);
return $catelist;
?>