<?php
#============================
#	Filename: link.qgmod.php
#	Note	: 友情链接模块
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-5-11
#============================
global $FS,$DB,$prefix;
if($type != "PIC" && $type != "TXT" && $type != "ALL")
{
	$type = "ALL";
}
$md5 = md5($type."_link");
$cache_file = "data/cache/link_".$md5.".php";#[缓存文件]
$check_status = false;
if($iscache)
{
	$check_status = CheckCache($cache_file);#[判断缓存文件的存储状态]
}
if($check_status)
{
	include_once($cache_file);
	return $list;
}
$sql = "SELECT * FROM ".$prefix."link";
if($type == "PIC")
{
	$sql .= " WHERE picture !=''";
}
elseif($type == "TXT")
{
	$sql .= " WHERE picture=''";
}
$sql .= " ORDER BY taxis ASC,id DESC";
$list = $DB->qgGetAll($sql);
if(!$list)
{
	return false;
}
$FS->qgWrite($list,$cache_file,"list");
unset($cache_file);
return $list;
?>