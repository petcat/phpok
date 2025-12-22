<?php
#============================
#	Filename: nav.qg.php
#	Note	: 导航管理
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-02-12
#============================
function update_nav_file()
{
	global $language;
	global $DB,$prefix;
	global $FS;
	$sql = "SELECT * FROM ".$prefix."nav WHERE language='".$language."' ORDER BY taxis ASC,id DESC";
	$rslist = $DB->qgGetAll($sql);
	$FS->qgWrite($rslist,"data/nav_".$language.".php","qgnav");
	return true;
}

#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["nav"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}

if($sys_act == "add")
{
	#[显示当前语言分类]
	include_once("class/unlimited_category.class.php");
	$CT = new Category();
	#[显示组分类]
	$rslist = $DB->qgGetAll("SELECT id,rootid,parentid,catename,status FROM ".$prefix."category WHERE language='".$language."'");
	$catelist = $CT->arraySet($rslist,0);
	unset($rslist);
	#[显示专题分类]
	$spelist = $DB->qgGetAll("SELECT id,subject FROM ".$prefix."special WHERE language='".$language."' AND ifcheck='1' ORDER BY taxis ASC,id DESC");
	#[CSS样式管理]
	$csslist = csslist();
	Foot("nav.add.qg");
}
elseif($sys_act == "addok")
{
	$navtype = SafeHtml($navtype);
	if($navtype == "system")
	{
		$systemid = intval($systemid);
		if(!$systemid)
		{
			Error("请选择一下分类","admin.php?file=nav&act=add");
		}
		$rs = $DB->qgGetOne("SELECT id,catename,catestyle FROM ".$prefix."category WHERE id='".$systemid."' AND language='".$language."'");
		if(!$rs)
		{
			Error("没有找到相关信息...","admin.php?file=nav&act=add");
		}
		$subject = $rs["catename"];
		$style = $rs["catestyle"];
		$link = "list.php?id=".$rs["id"];
	}
	elseif($navtype == "special")
	{
		$specialid = intval($specialid);
		if(!$specialid)
		{
			Error("请选择一个专题","admin.php?file=nav&act=add");
		}
		$rs = $DB->qgGetOne("SELECT id,subject,style FROM ".$prefix."special WHERE id='".$specialid."' AND language='".$language."'");
		if(!$rs)
		{
			Error("没有找到相关信息...","admin.php?file=nav&act=add");
		}
		$subject = $rs["subject"];
		$style = $rs["style"];
		$link = "special.php?id=".$rs["id"];
	}
	else
	{
		$subject = SafeHtml($subject);
		$link = SafeHtml($link);
		$style = SafeHtml($style);
	}
	$target = intval($target);
	$taxis = intval($taxis);
	$sql = "INSERT INTO ".$prefix."nav(name,css,url,target,taxis,language) VALUES('".$subject."','".$style."','".$link."','".$target."','".$taxis."','".$language."')";
	$DB->qgQuery($sql);
	update_nav_file();
	Error("导航添加成功...","admin.php?file=nav&act=list");
}
elseif($sys_act == "modify")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法...","admin.php?file=nav&act=list");
	}
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."nav WHERE id='".$id."'");
	if(!$rs)
	{
		Error("没有找到信息...","admin.php?file=nav&act=list");
	}
	$csslist = csslist();
	Foot("nav.modify.qg");
}
elseif($sys_act == "modifyok")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法...","admin.php?file=nav&act=list");
	}
	$subject = SafeHtml($subject);
	$link = SafeHtml($link);
	$style = SafeHtml($style);
	$target = intval($target);
	$taxis = intval($taxis);
	$sql = "UPDATE ".$prefix."nav SET name='".$subject."',css='".$style."',url='".$link."',target='".$target."',taxis='".$taxis."' WHERE id='".$id."'";
	$DB->qgQuery($sql);
	update_nav_file();
	Error("导航编辑成功...","admin.php?file=nav&act=list");
}
elseif($sys_act == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法...","admin.php?file=nav&act=list");
	}
	$DB->qgQuery("DELETE FROM ".$prefix."nav WHERE id='".$id."'");
	update_nav_file();
	Error("操作成功...","admin.php?file=nav&act=list");
}
else
{
	$sql = "SELECT * FROM ".$prefix."nav WHERE language='".$language."' ORDER BY taxis ASC,id DESC";
	$rslist = $DB->qgGetAll($sql);
	Foot("nav.list.qg");
}
?>