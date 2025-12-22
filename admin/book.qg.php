<?php
#[留言本]
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["book_feedback"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
$r_url = "admin.php?file=book";
if($sysact == "delete")
{
	$r_url = $_SESSION["return_url"] ? $_SESSION["return_url"] : $r_url."&act=list";
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法",$r_url);
	}
	$sql = "DELETE FROM ".$prefix."book WHERE id='".$id."'";
	$rs = $DB->qgQuery($sql);
	Error("留言主题ID：".$id." 已成功删除",$r_url);
}
elseif($sysAct == "plset")
{
	$myidlist = $STR->safe($idlist);#[获取IDlist]
	if(!$myidlist)
	{
		Error("信息操作不正确","admin.php?file=book&act=list");
	}
	$qgtype = $STR->safe($qgtype);
	if($qgtype == "delete")
	{
		$DB->qgQuery("DELETE FROM ".$prefix."book WHERE id in(".$myidlist.")");#[删除主题]
		Error("批量删除主题完成！","admin.php?file=book&act=list");
	}
	elseif($qgtype == "dcheck")
	{
		$DB->qgQuery("UPDATE ".$prefix."book SET ifcheck=0 WHERE id in(".$myidlist.")");
		Error("批量 <span style='color:red;'>取消审核</span> 操作完成！","admin.php?file=book&act=list");
	}
	else
	{
		$DB->qgQuery("UPDATE ".$prefix."book SET ifcheck=1 WHERE id in(".$myidlist.")");
		Error("批量 <span style='color:red;'>审核</span> 操作完成！","admin.php?file=book&act=list");
	}
}
elseif($sysAct == "view")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=book&act=list");
	}
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."book WHERE id='".$id."'");
	$fckeditor = FckEditor("reply",FckToHtml($rs["reply"]),"LongDefault","300px","100%");
}
elseif($sysAct == "viewok")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=book&act=list");
	}
	$ifcheck = intval($ifcheck);
	$subject = $STR->safe($subject);
	$qguser = $STR->safe($qguser);
	$typeid = intval($cateid);
	$content = $STR->safe($content);
	$reply = $STR->html($reply);
	$replydate = $replydate ? strtotime($replydate) : $system_now;
	$email = $STR->safe($email);
	$DB->qgQuery("UPDATE ".$prefix."book SET typeid='".$typeid."',user='".$qguser."',subject='".$subject."',content='".$content."',email='".$email."',ifcheck='".$ifcheck."',reply='".$reply."',replydate='".$replydate."' WHERE id='".$id."'");
	Error("留言内容编辑/回复成功！","admin.php?file=book&act=list");
}
else
{
	#[留言列表]
	$psize = 30;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	$page_url = "admin.php?file=book&act=list";
	$stype = $stype ? $STR->safe($stype) : "subject";
	$keywords = $STR->safe($keywords);
	$condition = "WHERE language='".$language."'";
	if($keywords)
	{
		$page_url .= "&stype=".rawurlencode($stype);
		$page_url .= "&keywords=".rawurlencode($keywords);
		if($stype == "subject")
		{
			$condition .= " AND subject LIKE '%".$keywords."%'";
		}
		elseif($stype == "user")
		{
			$condition .= " AND user LIKE '%".$keywords."%'";
		}
		elseif($stype == "email")
		{
			$condition .= " AND email LIKE '%".$keywords."%'";
		}
	}
	$ifcheck = intval($ifcheck);
	if($ifcheck)
	{
		$page_url .= "&ifcheck=".$ifcheck;
		if($ifcheck == 1)
		{
			$condition .= " AND ifcheck>0";
		}
		else
		{
			$condition .= " AND ifcheck=0";
		}
	}
	$typelist[0]["typename"] = "未分类留言";
	#[计算信息数]
	$bcount = $DB->qgCount("SELECT id FROM ".$prefix."book ".$condition);
	$rslist = $DB->qgGetAll("SELECT id,typeid,userid,user,subject,postdate,email,ifcheck FROM ".$prefix."book ".$condition." ORDER BY postdate DESC,id DESC LIMIT ".$offset.",".$psize);
	$booklist = array();
	foreach($rslist AS $key=>$value)
	{
		$value["cut_subject"] = CutString($value["subject"],40);
		$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);
		$booklist[] = $value;
	}
	$pagelist = page($page_url,$bcount,$psize,$pageid);#[获取页数信息]
	$_SESSION["return_url"] = $page_url."&pageid=".$pageid;
}
Foot("book.qg");
?>