<?php
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["link"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
#[友情链接信息管理]
if($sysAct == "add" || $sysAct == "modify")
{
	#[添加友情链接]
	if($sysAct == "modify")
	{
		$id = intval($id);
		if(!$id)
		{
			Error("操作非法！","admin.php?file=link&act=list");
		}
		$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."link WHERE id='".$id."'");
	}
}
elseif($sysAct == "setok")
{
	$name = $STR->safe($name);
	$qgurl = $STR->safe($qgurl);
	$picture = $STR->safe($picture);
	$taxis = intval($taxis);
	if($id)
	{
		if(!$name || !$qgurl)
		{
			Error("名称或网址为空","admin.php?file=link&act=modify&id=".$id);
		}
		$DB->qgQuery("UPDATE ".$prefix."link SET name='".$name."',url='".$qgurl."',picture='".$picture."',taxis='".$taxis."' WHERE id='".$id."'");
		Error("友情链接编辑成功","admin.php?file=link&act=list");
	}
	else
	{
		if(!$name || !$qgurl)
		{
			Error("名称或网址为空","admin.php?file=link&act=add");
		}
		$DB->qgQuery("INSERT INTO ".$prefix."link(name,url,picture,taxis) VALUES('".$name."','".$qgurl."','".$picture."','".$taxis."')");
		Error("友情链接添加成功","admin.php?file=link&act=list");
	}
}
elseif($sysAct == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法","admin.php?file=link&act=list");
	}
	$DB->qgQuery("DELETE FROM ".$prefix."link WHERE id='".$id."'");
	Error("友情链接删除成功","admin.php?file=link&act=list");
}
else
{
	$msglist = $DB->qgGetAll("SELECT id,name,taxis,url FROM ".$prefix."link ORDER BY taxis ASC,id DESC LIMIT 0,1000");
}
Foot("link.qg");
?>