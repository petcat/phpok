<?php
#============================
#	Filename: online.qg.php
#	Note	: 在线客服代码
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-03-04
#============================
$online_file = "data/online_help.php";
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["online"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
if($sysact == "setok")
{
	$content = FckHtml($content,false);
	$FS->qgWrite($content,$online_file);
	Error("在线客服代码更新成功！","admin.php?file=online");
}
else
{
	$content = $FS->qgRead($online_file);
	$fckeditor = FckEditor("content",$content,"LongDefault","300px","100%");
	Foot("online.qg");
}
?>