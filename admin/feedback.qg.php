<?php
#[反馈]
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["book_feedback"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
$r_url = "admin.php?file=feedback";
if($sysAct == "plset")
{
	$myidlist = $STR->safe($idlist);#[获取IDlist]
	if(!$myidlist)
	{
		Error("信息操作不正确","admin.php?file=feedback&act=list");
	}
	$qgtype = $STR->safe($qgtype);
	$DB->qgQuery("DELETE FROM ".$prefix."feedback WHERE id in(".$myidlist.")");#[删除主题]
	Error("批量删除完成！","admin.php?file=feedback&act=list");
}
elseif($sysAct == "view")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=feedback&act=list");
	}
	$sql = "SELECT f.*,u.user FROM ".$prefix."feedback AS f JOIN ".$prefix."user AS u ON f.userid=u.id WHERE f.id='".$id."'";
	$rs = $DB->qgGetOne($sql);
	$fckeditor = FckEditor("reply",FckToHtml($rs["reply"]),"Default","300px","100%");
}
elseif($sysAct == "viewok")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=feedback&act=list");
	}
	$subject = $STR->safe($subject);
	$content = $STR->safe($content);
	$reply = $STR->html($reply);
	$replydate = $replydate ? strtotime($replydate) : $system_time;
	$sql = "UPDATE ".$prefix."feedback SET subject='".$subject."',content='".$content."',reply='".$reply."',replydate='".$replydate."' WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("反馈内容编辑/回复成功！","admin.php?file=feedback&act=list");
}
else
{
	#[反馈列表]
	$psize = 30;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	$page_url = "admin.php?file=feedback&act=list";
	$stype = $stype ? $STR->safe($stype) : "subject";
	$keywords = $STR->safe($keywords);
	$condition = "WHERE 1";
	if($keywords)
	{
		$page_url .= "&keywords=".rawurlencode($keywords);
	}
	$sql = "SELECT count(id) FROM ".$prefix."feedback ".$condition;
	$bcount = $DB->qg_count($sql);
	$rslist = $DB->qgGetAll("SELECT id,subject,postdate FROM ".$prefix."feedback ".$condition." ORDER BY postdate DESC,id DESC LIMIT ".$offset.",".$psize);
	$feedbacklist = array();
	foreach($rslist AS $key=>$value)
	{
		$value["cut_subject"] = CutString($value["subject"],40);
		$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);
		$feedbacklist[] = $value;
	}
	$pagelist = page($page_url,$bcount,$psize,$pageid);#[获取页数信息]
	$_SESSION["return_url"] = $page_url."&pageid=".$pageid;
}
Foot("feedback.qg");
?>