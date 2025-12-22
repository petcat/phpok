<?php
$r_url = "admin.php?file=user";
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["user"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
if($sysact == "add" || $sysact == "modify")
{
	if($sysAct == "modify")
	{
		$id = intval($id);
		if(!$id)
		{
			Error("操作非法！","admin.php?file=user&act=list");
		}
		$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."user WHERE id='".$id."'");
	}
}
elseif($sysact == "viewok")
{
	$id = intval($id);
	$msg = $STR->safe($_POST);
	$msg["regdate"] = $msg["regdate"] ? strtotime($msg["regdate"]) : $system_time;
	if(!$msg["username"])
	{
		Error("用户名不能为空",$r_url."&act=".($id ? "modify" : "add")."&id=".$id);
	}
	if($id)
	{
		$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."user WHERE id='".$id."'");
		$sql = "SELECT user FROM ".$prefix."user WHERE id!='".$id."' AND user='".$msg["username"]."'";
		$rschk = $DB->qgGetOne($sql);
		if($rschk)
		{
			Error("会员账号 ".$msg["username"]." 已经存在",$r_url."&act=modify&id=".$id);
		}
		$msg["password"] = $msg["password"] ? $msg["password"] : "123456";
		$password = $rs["pass"] == $msg["password"] ? $rs["pass"] : md5($msg["password"]);
		$sql = "UPDATE ".$prefix."user SET user='".$msg["username"]."',nickname='".$msg["username"]."',realname='".$msg["realname"]."',pass='".$password."',phone='".$msg["phone"]."',email='".$msg["email"]."',address='".$msg["address"]."',postmail='".$msg["zipcode"]."' WHERE id='".$id."'";
		$DB->qgQuery($sql);
		$r_url = $_SESSION["return_url"] ? $_SESSION["return_url"] : $r_url."&act=list";
		Error("会员信息更新成功",$r_url);
	}
	else
	{
		$sql = "SELECT user FROM ".$prefix."user WHERE user='".$msg["username"]."'";
		$rschk = $DB->qgGetOne($sql);
		if($rschk)
		{
			Error("会员账号 ".$msg["username"]." 已经存在",$r_url."&act=add");
		}
		$password = $msg["password"] ? md5($rs["password"]) : md5("123456");
		$sql = "INSERT INTO ".$prefix."user(user,nickname,realname,pass,email,phone,address,postmail,regdate) VALUES('".$msg["username"]."','".$msg["username"]."','".$msg["realname"]."','".$password."','".$msg["email"]."','".$msg["phone"]."','".$msg["address"]."','".$msg["zipcode"]."','".$system_time."')";
		$DB->qgQuery($sql);
		Error("会员信息添加成功",$r_url."&act=list");
	}
}
elseif($sysact == "delete")
{
	$r_url = $_SESSION["return_url"] ? $_SESSION["return_url"] : $r_url."&act=list";
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法",$r_url);
	}
	$sql = "DELETE FROM ".$prefix."user WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("会员信息删除成功",$r_url);
}
elseif($act == "list")
{
	$page_url = "admin.php?file=user&act=list";
	$psize = 30;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	$condition = "WHERE 1";
	$keywords = SafeHtml($keywords);
	$stype = SafeHtml($stype);
	if($keywords)
	{
		if($stype == "nickname")
		{
			$condition .= " AND nickname LIKE '%".$keywords."%'";
		}
		elseif($stype == "realname")
		{
			$condition .= " AND realname LIKE '%".$keywords."%'";
		}
		elseif($stype == "email")
		{
			$condition .= " AND email LIKE '%".$keywords."%'";
		}
		elseif($stype == "address")
		{
			$condition .= " AND address LIKE '%".$keywords."%'";
		}
		elseif($stype == "phone")
		{
			$condition .= " AND phone LIKE '%".$keywords."%'";
		}
		else
		{
			$condition .= " AND user LIKE '%".$keywords."%'";
		}
		$page_url .= "&stype=".rawurlencode($stype)."&keywords=".rawurlencode($keywords);
	}
	$count = $DB->qg_count("SELECT count(id) FROM ".$prefix."user ".$condition);
	$pagelist = page($page_url,$count,$psize,$pageid);#[获取页数信息]
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."user ".$condition." ORDER BY id DESC LIMIT ".$offset.",".$psize);
}
Foot("user.qg");
?>