<?php
#============================
#	Filename: filename.php
#	Note	: Note
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-2-14
#============================
global $FS,$DB,$prefix;
if($ispic)
{
	$md5 = md5(LANGUAGE_ID."_".$cateid."_".$length."_".$orderby."_1_".$limit);
}
else
{
	$md5 = md5(LANGUAGE_ID."_".$cateid."_".$length."_".$orderby."_0_".$limit);
}
$cache_file = "data/cache/list_".$md5.".php";#[缓存文件]
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
#[取得当前分类的信息]
$rs = $DB->qgGetOne("SELECT catename FROM ".$prefix."category WHERE id='".$cateid."'");
if(!$rs)
{
	unset($cache_file);
	return false;
}
$list["catename"] = $rs["catename"];
$list["id"] = $cateid;
$list["cateid"] = $cateid;
$list["url"] = "list.php?id=".$cateid;
unset($rs);
if($ispic)
{
	$sql = "SELECT m.*,u.filename AS u_filename,u.thumbfile AS u_thumbfile,u.markfile AS u_markfile,u.folder AS u_folder,u.filetype AS u_filetype FROM ".$prefix."msg AS m,".$prefix."category AS c,".$prefix."upfiles AS u WHERE (c.id='".$cateid."' OR c.rootid='".$cateid."' OR c.parentid='".$cateid."') AND m.thumb=u.id AND m.ifcheck='1' AND m.cateid=c.id";
}
else
{
	$sql = "SELECT m.*,u.filename AS u_filename,u.thumbfile AS u_thumbfile,u.markfile AS u_markfile,u.folder AS u_folder FROM ".$prefix."category AS c,".$prefix."msg AS m LEFT JOIN ".$prefix."upfiles AS u ON m.thumb=u.id WHERE  (c.id='".$cateid."' OR c.rootid='".$cateid."' OR c.parentid='".$cateid."') AND m.ifcheck='1' AND m.cateid=c.id";
}
$sql .= " ORDER BY ";
$order_list = _____QGMODULE_ORDERBY($orderby,"m");
$sql .= implode(",",$order_list);
unset($order_list);
$sql .= " LIMIT 0,".$limit;
$rslist = $DB->qgGetAll($sql,true);
if(!$rslist)
{
	unset($sql,$cache_file);
	return false;
}
$msglist = _____QGMODULE_CLEARUP_LIST($rslist,$length,true);
$list["list"] = $msglist;
$FS->qgWrite($list,$cache_file,"list");
unset($rslist,$msglist,$cache_file);
return $list;
?>