<?php
#============================
#	Filename: piclist_all.qgmod.php
#	Note	: 图片根据类别来调用
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-2-14
#============================
global $FS,$DB,$prefix;
$md5 = md5(LANGUAGE_ID."_".$sign."_".$type."_".$limit);
$cache_file = "data/cache/picall_".$md5.".php";#[缓存文件]
$check_status = false;
if($iscache)
{
	$check_status = CheckCache($cache_file);#[判断缓存文件的存储状态]
}
if($check_status)
{
	include_once($cache_file);
	unset($cache_file);
	return $list;
}
#[读取组信息]
if($type)
{
	$sql = "SELECT c.id FROM ".$prefix."category AS c,".$prefix."sysgroup AS s WHERE c.sysgroupid=s.id AND s.sign='".$type."' AND c.status='1' AND c.language='".LANGUAGE_ID."'";
}
else
{
	$sql = "SELECT id FROM ".$prefix."category WHERE status='1' AND language='".LANGUAGE_ID."'";
}
$rslist = $DB->qgGetAll($sql);
unset($sql);
if(!$rslist)
{
	unset($cache_file);
	return false;
}
foreach($rslist AS $key=>$value)
{
	$catelist[] = $value["id"];
}
unset($rslist);
$sql = "SELECT m.*,c.catename,u.filename AS u_filename,u.filetype AS u_filetype,u.thumbfile AS u_thumbfile,u.markfile AS u_markfile,u.folder AS u_folder FROM ".$prefix."msg AS m,".$prefix."category AS c,".$prefix."upfiles AS u WHERE m.cateid in(".implode(",",$catelist).") AND m.ifcheck='1' AND m.cateid=c.id AND m.thumb=u.id ORDER BY";
if($sign == "hot")
{
	$sql .= " m.istop DESC,m.hits DESC,m.orderdate DESC,m.postdate DESC,m.id DESC";
}
elseif($sign == "cold")
{
	$sql .= " m.istop DESC,m.hits ASC,m.orderdate DESC,m.postdate DESC,m.id DESC";
}
elseif($sign == "vouch")
{
	$sql .= " m.istop DESC,m.isvouch DESC,m.orderdate DESC,m.postdate DESC,m.id DESC";
}
elseif($sign == "rand")
{
	$sql .= " rand()";
}
else
{
	$sql .= " m.istop DESC,m.orderdate DESC,m.postdate DESC,m.id DESC";
}
$sql .= " LIMIT 0,".$limit;
$rslist = $DB->qgGetAll($sql);
unset($sql);
if(!$rslist)
{
	return false;
}
$list = _____QGMODULE_CLEARUP_LIST($rslist,0,true);
$FS->qgWrite($list,$cache_file,"list");
unset($rslist,$cache_file);
return $list;
?>