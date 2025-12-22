<?php
#============================
#	Filename: ad.qgmod.php
#	Note	: 广告调用模块
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-2-14
#============================
global $DB,$prefix,$FS;
global $system_time;
$md5 = md5($id);
$cache_file = "data/cache/ad_".$md5.".php";#[缓存文件]
$check_status = false;
if($iscache)
{
	$check_status = CheckCache($cache_file);#[判断缓存文件的存储状态]
}
if($check_status)
{
	$ad = $FS->qgRead($cache_file);
	return;
}
$today = date("Y-m-d",$system_time);
$sql = "SELECT * FROM ".$prefix."ad WHERE id='".$id."' AND date(start_date)<='".$today."' AND date(end_date)>='".$today."' AND status='1'";
$rs = $DB->qgGetOne($sql);
if(!$rs)
{
	return;
}
$ad = "<!-- ".$rs["subject"]." -->".$rs["content"];
unset($rs);
$FS->qgWrite($ad,$cache_file);
?>