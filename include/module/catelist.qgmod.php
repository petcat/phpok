<?php
#============================
#	Filename: spelist.qgmod.php
#	Note	: 指定ID的分类
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-04-09
#============================
global $FS,$DB,$prefix;
if(!$inid)
{
	return false;
}
$md5 = md5(LANGUAGE_ID."_".$inid);
$cache_file = "data/cache/catelist_".$md5.".php";
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
$sql = "SELECT id,catename,catestyle,status FROM ".$prefix."category WHERE id in(".$inid.") AND status='1' AND language='".LANGUAGE_ID."' ORDER BY taxis ASC,id DESC";
$catelist = $DB->qgGetAll($sql);
if(!$catelist)
{
	return false;
}
$FS->qgWrite($catelist,$cache_file,"catelist");
unset($cache_file);
return $catelist;
?>