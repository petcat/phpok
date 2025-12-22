<?php
#[判断权限]
if($_SESSION["admin"]["typer"] != "system")
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
if($sysAct == "set")
{
	if(!$system && file_exists("data/system_".$language.".php"))
	{
		include_once("data/system_".$language.".php");
	}
	$rs = $system;
	Foot("system.qg");
}
elseif($sysAct == "setok")
{
	$rs_msg = $STR->safe($_POST);
	$FS->qgWrite($rs_msg,"data/system_".$language.".php","system");
	Error("网站常规信息设置成功！","admin.php?file=system&act=set");
}
?>