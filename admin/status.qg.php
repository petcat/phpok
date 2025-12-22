<?php
#============================
#	Filename: status.qg.php
#	Note	: 网站状态属性
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-02-12
#============================
#[判断权限]
if($_SESSION["admin"]["typer"] != "system")
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}

if($sys_act == "setok")
{
	$status = intval($status);
	$content = SafeHtml($content);
	if(!$status)
	{
		$FS->qgDelete("data/site.lock.php");
	}
	else
	{
		$FS->qgWrite($content,"data/site.lock.php");
	}
	Error("设置成功...","admin.php?file=status&act=list");
}
else
{
	if(file_exists("data/site.lock.php"))
	{
		$status = 1;
		$content = $FS->qgRead("data/site.lock.php");
	}
	else
	{
		$status = 0;
		$content = "";
	}
	Foot("status.qg");
}
?>