<?php
#[公告信息管理]
$r_url = "admin.php?file=notice";
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["notice"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
if($sysact == "view")
{
	$id = intval($id);
	if($id)
	{
		$sql = "SELECT * FROM ".$prefix."notice WHERE id='".$id."'";
		$rs = $DB->qgGetOne($sql);
		$rs["postdate"] = date("Y-m-d H:i:s",$rs["postdate"]);
		$fckeditor = FckEditor("content",FckToHtml($rs["content"]),"Default","400px","100%");
	}
	else
	{
		$rs["subject"] = $rs["content"] = "";
		$rs["content"] = FckToHtml($rs["content"]);
		$rs["postdate"] = date("Y-m-d H:i:s",$system_time);
		$fckeditor = FckEditor("content","","Default","400px","100%");
	}
}
elseif($act == "viewok")
{
	$id = intval($id);
	$subject = $STR->safe($subject);
	$content = $STR->html($content);
	$notice_url = $STR->safe($notice_url);
	$postdate = $STR->safe($postdate) ? strtotime($STR->safe($postdate)) : $system_time;
	if(!$content && !$notice_url)
	{
		Error("内容或网址必须有一个不为空",$r_url."&act=view&id=".$id);
	}
	if(!$subject)
	{
		Error("主题不能为空！",$r_url."&act=view&id=".$id);
	}
	if($id)
	{
		$sql = "UPDATE ".$prefix."notice SET subject='".$subject."',url='".$notice_url."',content='".$content."',postdate='".$postdate."' WHERE id='".$id."'";
		$DB->qgQuery($sql);
		Error("公告更新成功！",$r_url."&act=list");
	}
	else
	{
		$sql = "INSERT INTO ".$prefix."notice(language,subject,url,content,postdate) VALUES('".$language."','".$subject."','".$notice_url."','".$content."','".$postdate."')";
		$DB->qgQuery($sql);
		Error("公告添加成功！",$r_url."&act=list");
	}
}
elseif($sysact == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法",$r_url."&act=list");
	}
	$sql = "DELETE FROM ".$prefix."notice WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("公告删除成功！",$r_url."&act=list");
}
else
{
	$rslist = $DB->qgGetAll("SELECT id,subject,postdate FROM ".$prefix."notice WHERE language='".$language."' ORDER BY id DESC LIMIT 0,100");
	$msglist = array();
	foreach($rslist AS $key=>$value)
	{
		$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);
		$msglist[] = $value;
	}
}
Foot("notice.qg");
?>