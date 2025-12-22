<?php
#[CSS样式管理器]
$tplid = intval($tplid);#[指定模板文件夹ID]
if(!$tplid)
{
	Error("操作不正确...","admin.php?file=tpl&act=list");
}
if($sys_act == "add")
{
	$sql = "SELECT id,style_id,ispage,type FROM ".$prefix."style WHERE language='".$language."'";
	$rslist = $DB->qgGetAll($sql);#[样式]
	$parent_style = $page_style = array();
	foreach($rslist AS $key=>$value)
	{
		if($value["ispage"] && $value["ispage"] != 0)
		{
			$page_style[] = $value;
		}
		else
		{
			$parent_style[] = $value;
		}
	}
	$TPL->set_file("style.add.qg");
	$TPL->n();
	Foot("style.add.qg");
}
else
{
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."style WHERE language='".$language."'");
	$TPL->set_file("style.list.qg");
	$TPL->n();
	Foot("style.list.qg");
}
?>