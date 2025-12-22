<?php
#[单页面管理]
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["special"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
if($sysAct == "view")
{
	$csslist = csslist();
	$id = intval($id);
	if(!$id)
	{
		$lead_title = "添加单页面信息";
		$fckeditor = FckEditor("content","","LongDefault","400px","100%");
	}
	else
	{
		$lead_title = "编辑单页面信息";
		$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."special WHERE id='".$id."'");
		$content = FckToHtml($rs["content"]);
		$fckeditor = FckEditor("content",$content,"LongDefault","400px","100%");
	}
}
elseif($sysAct == "viewok")
{
	$id = intval($id);
	$subject = $STR->safe($subject);
	$content = $STR->html($content);
	$myurl = $STR->safe($myurl);
	$ifcheck = intval($ifcheck);
	$style = $STR->safe($style);
	if(!$id)
	{
		$DB->qgQuery("INSERT INTO ".$prefix."special(subject,style,content,url,language,ifcheck,taxis) VALUES('".$subject."','".$style."','".$content."','".$myurl."','".$language."','".$ifcheck."','".$taxis."')");
		Error("内容创建成功！","admin.php?file=special&act=list");
	}
	else
	{
		$DB->qgQuery("UPDATE ".$prefix."special SET subject='".$subject."',style='".$style."',content='".$content."',ifcheck='".$ifcheck."',url='".$myurl."',taxis='".$taxis."' WHERE id='".$id."'");
		Error("内容编辑成功！","admin.php?file=special&act=list");
	}
}
elseif($sysact == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法","admin.php?file=special&act=list");
	}
	$DB->qgQuery("DELETE FROM ".$prefix."special WHERE id='".$id."'");
	Error("单页面信息删除成功","admin.php?file=special&act=list");
}
elseif($sysact == "status")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法","admin.php?file=special&act=list");
	}
	$rs = $DB->qgGetOne("SELECT ifcheck FROM ".$prefix."special WHERE id='".$id."'");
	if(!$rs)
	{
		Error("找不到相关内容信息","admin.php?file=special&act=list");
	}
	if($rs["ifcheck"] == 0 || !$rs["ifcheck"])
	{
		$ifcheck = 1;
		$msg = "成功设置为正常状态！";
	}
	else
	{
		$ifcheck = 0;
		$msg = "成功设置为锁定状态！";
	}
	$DB->qgQuery("UPDATE ".$prefix."special SET ifcheck='".$ifcheck."' WHERE id='".$id."'");
	Error($msg,"admin.php?file=special&act=list");
}
else
{
	#[专题列表]
	$rslist = $DB->qgGetAll("SELECT id,typeid,subject,ifcheck,url,taxis FROM ".$prefix."special WHERE language='".$language."'  ORDER BY taxis ASC,id DESC LIMIT 100");
	if(!$rslist)
	{
		$booklist = array();
	}
	else
	{
		foreach($rslist AS $key=>$value)
		{
			$value["cut_subject"] = CutString($value["subject"],40);
			$booklist[] = $value;
		}
	}
}
Foot("special.qg");
?>