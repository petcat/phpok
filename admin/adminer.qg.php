<?php
#[管理员添加管理]
if($_SESSION["admin"]["typer"] != "system")
{
	Error("只有系统管理员才有权限操作！","admin.php?file=index");
}
#[定义当前页的网页]
$r_url = "admin.php?file=adminer&act=";
if($sysact == "add" || $sysact == "modify")
{
	#[获取块信息功能]
	$modlist = $DB->qgGetAll("SELECT id,groupname FROM ".$prefix."sysgroup LIMIT 0,100");
	if($sysAct == "modify")
	{
		$id = intval($id);
		chk_id($id,$r_url."list");#[验证是否正确]
		$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."admin WHERE id='".$id."'");
		if($rs["user"] == $_SESSION["admin"]["user"])
		{
			Error("系统禁止更改自己信息",$r_url."list");
		}
		if($rs["typer"] != "system")
		{
			$module_list = explode(",",$rs["modulelist"]);
			foreach($module_list AS $key=>$value)
			{
				$module[$value] = true;
			}
		}
	}
}
elseif($sysact == "modifyok")
{
	$id = intval($id);
	chk_id($id,$r_url."list");#[验证是否正确]
	$msg = $STR->safe($_POST);
	if(count($msg["modulelist"])>0)
	{
		$modulelist = implode(",",$msg["modulelist"]);#[组合模块]
	}
	else
	{
		$modulelist = "";
	}
	$rschk = $DB->qgGetOne("SELECT user FROM ".$prefix."admin WHERE id!='".$id."' AND user='".$msg["username"]."'");
	if($rschk)
	{
		Error("管理员账号已经存在",$r_url."list");
	}
	$rs = $DB->qgGetOne("SELECT pass FROM ".$prefix."admin WHERE id='".$id."'");
	$msg["password"] = $msg["password"] ? $msg["password"] : "123456";
	$password = $rs["pass"] == $msg["password"] ? $rs["pass"] : md5($msg["password"]);
	$sql = "UPDATE ".$prefix."admin SET user='".$msg["username"]."',typer='".$msg["typer"]."',pass='".$password."',email='".$msg["email"]."',modulelist='".$modulelist."' WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("管理员信息编写成功",$r_url."list");
}
elseif($sysact == "addok")
{
	$msg = $STR->safe($_POST);
	if(count($msg["modulelist"])>0)
	{
		$modulelist = implode(",",$msg["modulelist"]);#[组合模块]
	}
	else
	{
		$modulelist = "";
	}
	#[检测账号是否被使用过了]
	$rschk = $DB->qgGetOne("SELECT user FROM ".$prefix."admin WHERE user='".$msg["username"]."'");
	if($rschk)
	{
		Error("管理员账号已被使用",$r_url."add");
	}
	$password = $msg["password"] ? md5($msg["password"]) : md5("123456");
	$sql = "INSERT INTO ".$prefix."admin(user,typer,pass,email,modulelist) VALUES('".$msg["username"]."','".$msg["typer"]."','".$password."','".$msg["email"]."','".$modulelist."')";
	$DB->qgQuery($sql);
	Error("管理员账号添加成功",$r_url."list");
}
elseif($sysact == "delete")
{
	$id = intval($id);
	chk_id($id,$r_url."list");#[验证是否正确]
	if($_SESSION["admin"]["typer"] != "system")
	{
		Error("仅限系统管理员才有删除其他管理员的权限！",$r_url."list");
	}
	if($_SESSION["admin"]["id"] == $id)
	{
		Error("管理员不能删除自己！",$r_url."list");
	}
	$sql = "DELETE FROM ".$prefix."admin WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("管理员删除成功",$r_url."list");
}
else
{
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."admin ".$condition." ORDER BY id DESC");
}
Foot("adminer.qg");
?>