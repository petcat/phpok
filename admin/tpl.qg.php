<?php
#[判断权限]
if($_SESSION["admin"]["typer"] != "system")
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
#[模板管理]
if($sys_act == "add")
{
	Foot("tpl.add.qg");
}
elseif($sys_act == "addok")
{
	$tplname = SafeHtml($tplname);
	$tplfolder = SafeHtml($tplfolder);
	$taxis = intval($taxis);
	if(!$tplname)
	{
		Error("显示名称不允许为空","admin.php?file=tpl&act=add");
	}
	if(!$tplfolder)
	{
		Error("指定的文件夹名称不允许为空！","admin.php?file=tpl&act=add");
	}
	$sql = "INSERT INTO ".$prefix."tpl(name,folder,taxis,language) VALUES('".$tplname."','".$tplfolder."','".$taxis."','".$language."')";
	$DB->qgQuery($sql);
	Error("添加成功！","admin.php?file=tpl&act=add");
}
elseif($sys_act == "modify")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法","admin.php?file=tpl");
	}
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."tpl WHERE id='".$id."'");
	if(!$rs)
	{
		Error("没有找到相关模板信息","admin.php?file=tpl");
	}
	Foot("tpl.modify.qg");
}
elseif($sys_act == "modifyok")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法","admin.php?file=tpl");
	}
	$tplname = SafeHtml($tplname);
	$tplfolder = SafeHtml($tplfolder);
	$taxis = intval($taxis);
	if(!$tplname)
	{
		Error("显示名称不允许为空","admin.php?file=tpl&act=modify&id=".$id);
	}
	if(!$tplfolder)
	{
		Error("指定的文件夹名称不允许为空！","admin.php?file=tpl&act=modify&id=".$id);
	}
	$sql = "UPDATE ".$prefix."tpl SET name='".$tplname."',folder='".$tplfolder."',taxis='".$taxis."' WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("风格信息更新成功","admin.php?file=tpl");
}
elseif($sys_act == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法","admin.php?file=tpl");
	}
	$sql = "DELETE FROM ".$prefix."tpl WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("风格已删除！","admin.php?file=tpl");
}
elseif($sysact == "setdefault")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法","admin.php?file=tpl");
	}
	$DB->qgQuery("UPDATE ".$prefix."tpl SET isdefault='0' WHERE language='".$language."'");
	$DB->qgQuery("UPDATE ".$prefix."tpl SET isdefault='1' WHERE id='".$id."' AND language='".$language."'");
	Error("操作成功","admin.php?file=tpl&act=list");
}
else
{
	$sql = "SELECT * FROM ".$prefix."tpl WHERE language='".$language."'";
	$rslist = $DB->qgGetAll($sql);
	Foot("tpl.list.qg");
}
?>
