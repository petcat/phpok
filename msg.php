<?php
#[内容信息]
require_once("global.php");
#[加载无限等级分类管理]
$id = intval($id);
if(!$id)
{
	qgheader();
}
#[获取主题信息]
$sql = "SELECT m.*,c.rootid,c.sysgroupid,c.parentid,c.tpl_msg,s.sign AS sysgroup_sign FROM ".$prefix."msg AS m,".$prefix."category AS c,".$prefix."sysgroup AS s WHERE m.id='".$id."' AND m.cateid=c.id AND c.sysgroupid=s.id";
$rs = $DB->qgGetOne($sql);
if(!$rs)
{
	qgheader();
}
$sysgroupid = $rs["sysgroupid"];
#[如果没有找到系统组]
if(!$sysgroupid)
{
	qgheader();
}
#[如果系统组标识为空]
if(!$rs["sysgroup_sign"])
{
	qgheader();
}
$sysgroup_sign = $rs["sysgroup_sign"];

unset($sql);

$DB->qgQuery("UPDATE ".$prefix."msg SET hits=hits+1 WHERE id='".$id."'");

$rs["postdate"] = date("Y-m-d H:i:s",$rs["postdate"]);
#[获取分类信息]
$cateid = $rs["cateid"];
if(!$cateid)
{
	qgheader();
}
define("QGMSG_CATEID",$cateid);#[定义常量QGMSG_CATEID，以供模块调用]

#[获取分类数组]
$sqlid = $rs["rootid"] ? $rs["rootid"] : $cateid;
$sql = "(SELECT id,sysgroupid,rootid,parentid,catename,catestyle FROM ".$prefix."category WHERE rootid='".$sqlid."' AND status=1 ORDER BY taxis ASC,id DESC) UNION (SELECT id,sysgroupid,rootid,parentid,catename,catestyle FROM ".$prefix."category WHERE id='".$sqlid."' AND status=1 ORDER BY taxis ASC,id DESC)";
$catelist = $DB->qgGetAll($sql);

#[获取排序]
$menulist = menu_list($catelist,$cateid);
#[标题头]
$sitetitle_list = array();
foreach($menulist AS $key=>$value)
{
	$sitetitle_list[] = $value["catename"];
}
$sitetitle = $rs["subject"]." - ".implode(" - ",$sitetitle_list)." - ".$system["sitename"];
#[向导栏]
$lead_menu = array();
#[对数组进行排序]
foreach($menulist AS $key=>$value)
{
	$temp = array();
	$temp["url"] = "list.php?id=".$value["id"];
	$temp["name"] = $value["catename"];
	$lead_menu[$key] = $temp;
	$m++;
}
#[获取根分类的]
unset($menulist);
$lead_menu = array_reverse($lead_menu);


#[显示同级分类]
$left_catelist = array();
$parentid = $rs["parentid"];
$cate_tpl_file = $rs["tpl_msg"];

foreach($catelist AS $key=>$value)
{
	if($parentid == $value["parentid"])
	{
		$left_catelist[] = $value;
	}
}


#[判断模板是否存在]
if($rs["tplfile"] && file_exists(NewTemplate."/".$rs["tplfile"]))
{
	$tpl_file = substr($rs["tplfile"],0,-4);
}
elseif($cate_tpl_file && (file_exists(NewTemplate."/".$cate_tpl_file) || file_exists(NewTemplate."/../default/".$cate_tpl_file)))
{
	$tpl_file = substr($cate_tpl_file,0,-4);
}
else
{
	if(!file_exists(NewTemplate."/".$sysgroup_sign.".msg.htm") && !file_exists(NewTemplate."/../default/".$sysgroup_sign.".msg.htm"))
	{
		qgheader();
	}
	$tpl_file = $sysgroup_sign.".msg";
}

#[获取内容信息]
$rsc = $DB->qgGetOne("SELECT * FROM ".$prefix."msg_content WHERE id='".$id."'");
$content = $rsc["content"];#[获取内容]
unset($rsc);
$pageid = intval($pageid);
$qg_array = ContentPageArray($content,"msg.php?id=".$id,$pageid);
$content = $qg_array[0];
$pagelist = $qg_array[1];

HEAD();
FOOT($tpl_file);
?>