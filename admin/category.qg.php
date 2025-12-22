<?php
#[载入分类管理系统]
require_once("class/unlimited_category.class.php");
$CT = new Category();
$sysgroupid = intval($sysgroupid);
if($sysgroupid)
{
	#[判断权限]
	$m = "msg_".$sysgroupid;
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP[$m])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
}

function LoopUpdateCategory($rootid,$parentid)
{
	global $language;
	global $DB,$prefix;
	if(is_array($parentid))
	{
		$pid = implode(",",$parentid);
	}
	else
	{
		$pid = intval($parentid);
	}
	if($pid)
	{
		$rs = $DB->qgGetAll("SELECT id FROM ".$prefix."category WHERE parentid IN(".$pid.") AND language='".$language."'");
		if($rs)
		{
			$array = array();
			foreach($rs AS $key=>$value)
			{
				$array[] = $value["id"];
			}
			$updateId = implode(",",$array);
			$DB->qgQuery("UPDATE ".$prefix."category SET rootid='".$rootid."' WHERE parentid IN(".$updateId.")");
			LoopUpdateCategory($rootid,$array);
		}
	}
	return true;
}


#[添加分类]
if($sysAct == "add")
{
	#[如果不存在sysgroupid且不存在parentid]
	if(!$sysgroupid && !$cateid)
	{
		Error("操作不正确！","admin.php?file=sysgroup&act=list");
	}
	if($cateid)
	{
		#[获取父分类的信息]
		$rs = $DB->qgGetOne("SELECT id,sysgroupid,rootid,parentid,catename FROM ".$prefix."category WHERE id='".$cateid."' AND language='".$language."'");
		$catename = $rs["catename"];
		$sysgroupid = $rs["sysgroupid"];
		$rootid = $rs["rootid"];
		$parentid = $rs["parentid"];
		unset($rs);
		#[判断权限]
		if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
		{
			Error("对不起，您没有权限操作当前功能","admin.php?file=index");
		}

	}
	#[如果sysgroupid]
	if($sysgroupid)
	{
		#[判断权限]
		if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
		{
			Error("对不起，您没有权限操作当前功能","admin.php?file=index");
		}

		$rs = $DB->qgGetOne("SELECT groupname,sign FROM ".$prefix."sysgroup WHERE id='".$sysgroupid."'");
		$groupname = $rs["groupname"];
		$groupsign = $rs["sign"];
		unset($rs);
		if(!$cateid)
		{
			#[获取当前组的分类管理]
			$rslist = $DB->qgGetAll("SELECT id,rootid,parentid,catename FROM ".$prefix."category WHERE sysgroupid='".$sysgroupid."' AND language='".$language."'");
			$catelist = $CT->arraySet($rslist,0);
			unset($rslist);
		}
	}
	#[显示样式]
	$csslist = csslist();
	#[加载添加分类的模板]
	Foot("category.add.qg");
}
elseif($sysAct == "addok")
{
	$msg = $STR->safe($_POST);
	$sysgroupid = intval($sysgroupid);
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	if(!$msg["catename"])
	{
		Error("分类名称不能为空","admin.php?file=category&act=add&sysgroupid=".$sysgroupid);
	}
	#[如果存在父分类]
	if($msg["cateid"])
	{
		$msg["parentid"] = $msg["cateid"];
		$rs = $DB->qgGetOne("SELECT rootid FROM ".$prefix."category WHERE id='".$msg["cateid"]."' AND language='".$language."'");
		if($rs["rootid"])
		{
			$msg["rootid"] = $rs["rootid"];
		}
		else
		{
			$msg["rootid"] = $msg["cateid"];
		}
	}
	else
	{
		$msg["rootid"] = 0;
		$msg["parentid"] = 0;
	}
	#[写入数据]
	$DB->qgQuery("INSERT INTO ".$prefix."category(sysgroupid,rootid,parentid,catename,catestyle,taxis,tpl_index,tpl_list,tpl_msg,note,status,language,psize,showtype) VALUES('".$msg["sysgroupid"]."','".$msg["rootid"]."','".$msg["parentid"]."','".$msg["catename"]."','".$msg["catestyle"]."','".$msg["taxis"]."','".$msg["tpl_index"]."','".$msg["tpl_list"]."','".$msg["tpl_msg"]."','".$msg["note"]."','".$msg["status"]."','".$language."','".$msg["psize"]."','".$msg["showtype"]."')");
	Error("分类信息添加成功...","admin.php?file=category&act=list&sysgroupid=".$sysgroupid);
}
elseif($sysAct == "list")
{
	if(!$sysgroupid)
	{
		Error("操作不正确！","admin.php?file=sysgroup&act=list");
	}
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$rs = $DB->qgGetOne("SELECT groupname FROM ".$prefix."sysgroup WHERE id='".$sysgroupid."'");
	$groupname = $rs["groupname"];
	unset($rs);
	#[显示列表信息]
	$rslist = $DB->qgGetAll("SELECT id,rootid,parentid,catename,status FROM ".$prefix."category WHERE sysgroupid='".$sysgroupid."' AND language='".$language."'");
	$catelist = $CT->arraySet($rslist,0);
	Foot("category.list.qg");
}
elseif($sysAct == "delete")
{
	if(!$sysgroupid)
	{
		Error("操作不正确！","admin.php?file=sysgroup&act=list");
	}
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法","admin.php?file=sysgroup&act=list");
	}
	#[检测是否存在子分类信息]
	$rs = $DB->qgGetOne("SELECT id FROM ".$prefix."category WHERE rootid='".$id."' OR parentid='".$id."'");
	if($rs)
	{
		Error("已存在子分类，不允许删除","admin.php?file=category&act=list&sysgroupid=".$sysgroupid);
	}
	#[检测是否有已审核的信息]
	$rs = $DB->qgGetOne("SElECT id FROM ".$prefix."msg WHERE cateid='".$id."'");
	if($rs)
	{
		Error("已存在内容信息，不允许删除","admin.php?file=category&act=list&sysgroupid=".$sysgroupid);
	}
	#[删除分类信息]
	$DB->qgQuery("DELETE FROM ".$prefix."category WHERE id='".$id."'");
	Error("分类已删除成功","admin.php?file=category&act=list&sysgroupid=".$sysgroupid);
}
elseif($sysAct == "modify")
{
	#[编辑分类]
	$id = intval($id);
	if(!$id)
	{
		Error("无法获取分类ID信息","admin.php?file=sysgroup&act=list");
	}
	#[获取分类信息]
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."category WHERE id='".$id."' AND language='".$language."'");
	if(!$rs)
	{
		Error("无法获取数据...","admin.php?file=sysgroup&act=list");
	}
	$sysgroupid = $rs["sysgroupid"];
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$rsGroup = $DB->qgGetOne("SELECT groupname,sign FROM ".$prefix."sysgroup WHERE id='".$sysgroupid."'");
	$groupname = $rsGroup["groupname"];
	$groupsign = $rsGroup["sign"];
	unset($rsGroup);

	$rslist = $DB->qgGetAll("SELECT id,rootid,parentid,catename FROM ".$prefix."category WHERE sysgroupid='".$sysgroupid."' AND id!='".$id."' AND language='".$language."'");
	$catelist = $CT->arraySet($rslist,0);
	unset($rslist);
	#[显示样式]
	$csslist = csslist();
	Foot("category.modify.qg");
}
elseif($sysAct == "modifyok")
{
	if(!$sysgroupid)
	{
		Error("操作不正确！","admin.php?file=sysgroup&act=list");
	}
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$id = intval($id);
	if(!$id)
	{
		if(!$sysgroupid && !$id)
		{
			Error("分类ID不存在","admin.php?file=sysgroup&act=list");
		}
		elseif($sysgroupid && !$id)
		{
			Error("分类ID不存在","admin.php?file=category&act=list&sysgroupid=".$sysgroupid);
		}
	}
	$msg = array();
	foreach($_POST AS $key=>$value)
	{
		$value = SafeHtml($value);
		$msg[$key] = $value;
	}
	$oldRs = $DB->qgGetOne("SELECT parentid FROM ".$prefix."category WHERE id='".$id."' AND language='".$language."'");
	$oldPid = $oldRs["parentid"];
	$newPid = $msg["cateid"];
	#[如果更改了新旧分类]
	if($newPid != $oldPid)
	{
		#[如果新的分类ID为根分类ID]
		if($newPid == 0 || !$newPid)
		{
			$parentid = 0;
			$rootid = 0;
		}
		else
		{
			#[获取新分类下的根分类ID号]
			$new_cate = $DB->qgGetOne("SELECT rootid FROM ".$prefix."category WHERE id='".$newPid."' AND language='".$language."'");
			#[修正移动分类时的错误]
			$rootid = $new_cate["rootid"] ? $new_cate["rootid"] : $newPid;
			#$rootid = $new_cate["rootid"] ? $new_cate["rootid"] : 0;
			$parentid = $newPid;
		}
		#[更新当前分类的父子关系]
		$DB->qgQuery("UPDATE ".$prefix."category SET rootid='".$rootid."',parentid='".$parentid."' WHERE id='".$id."'");
		#[如果新旧分类的rootid一样则进行以下操作]
		if($oldRs["rootid"] == $rootid)
		{
			$DB->qgQuery("UPDATE ".$prefix."category SET parentid='".$parentid."' WHERE parentid='".$id."'");
		}
		else
		{
			#[如果新旧分类不一致]
			if($rootid)
			{
				#[如果存在根分类ID]
				$DB->qgQuery("UPDATE ".$prefix."category SET rootid='".$rootid."' WHERE parentid='".$id."'");
				LoopUpdateCategory($rootid,$id);
			}
			else
			{
				$DB->qgQuery("UPDATE ".$prefix."category SET rootid='".$id."' WHERE parentid='".$id."'");
				LoopUpdateCategory($id,$id);
			}
		}
	}
	#[更新内容信息]
	$DB->qgQuery("UPDATE ".$prefix."category SET catename='".$msg["catename"]."',catestyle='".$msg["catestyle"]."',taxis='".$msg["taxis"]."',tpl_index='".$msg["tpl_index"]."',tpl_list='".$msg["tpl_list"]."',tpl_msg='".$msg["tpl_msg"]."',note='".$msg["note"]."',status='".$msg["status"]."',psize='".$msg["psize"]."',showtype='".$msg["showtype"]."' WHERE id='".$id."'");
	Error("分类信息编辑成功...","admin.php?file=category&act=list&sysgroupid=".$sysgroupid);
}
?>