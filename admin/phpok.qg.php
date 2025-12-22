<?php
#============================
#	Filename: phpok.qg.php
#	Note	: 广告管理系统
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-02-28
#============================
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["ad"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
if($sysact == "add" || $sysact == "modify")
{
	if($sysact == "modify")
	{
		$id = intval($id);
		if(!$id)
		{
			Error("操作非法...","admin.php?file=phpok&act=list");
		}
		$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."ad WHERE id='".$id."'");
		$content = FckToHtml($rs["content"]);
	}
	else
	{
		$content = "";
		$rs["status"] = 1;
		$rs["start_date"] = date("Y-m-d",$system_time);
		$rs["end_date"] = date("Y-m-d",($system_time + 24*60*60*360));
	}
	$fckeditor = FckEditor("content",$content,"LongDefault","300px","100%");
}
elseif($sysact == "viewok")
{
	$id = intval($id);
	$subject = $STR->safe($subject);
	$typeid = intval($typeid);
	$STR->set("script",true);#[设置允许使用script]
	$content = $STR->html($content);
	$status = intval($status);
	$start_date = $STR->safe($start_date);
	$end_date = $STR->safe($end_date);
	if(!$id)
	{
		$sql = "INSERT INTO ".$prefix."ad(subject,content,status,start_date,end_date) VALUES";
		$sql.= "('".$subject."','".$content."','".$status."','".$start_date."','".$end_date."')";
		$DB->qgQuery($sql);
		Error("广告创建成功！","admin.php?file=phpok&act=list");
	}
	else
	{
		$sql = "UPDATE ".$prefix."ad SET subject='".$subject."',";
		$sql.= "content='".$content."',status='".$status."',start_date='".$start_date."',end_date='".$end_date."'";
		$sql.= " WHERE id='".$id."'";
		$DB->qgQuery($sql);
		Error("广告内容编辑成功！","admin.php?file=phpok&act=list");
	}
}
elseif($sysact == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法...","admin.php?file=phpok&act=list");
	}
	$DB->qgQuery("DELETE FROM ".$prefix."ad WHERE id='".$id."'");
	Error("广告删除成功，请更新缓存…","admin.php?file=phpok&act=list");
}
else
{
	#[广告列表]
	$psize = 30;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	$page_url = "admin.php?file=phpok&act=list";
	$keywords = $STR->safe($keywords);
	$condition = "WHERE 1";
	if($keywords)
	{
		$page_url .= "&keywords=".rawurlencode($keywords);
		$condition .= " AND subject LIKE '%".$keywords."%'";
	}
	$ifcheck = intval($ifcheck);
	if($ifcheck)
	{
		$page_url .= "&ifcheck=".$ifcheck;
		if($ifcheck == 1)
		{
			$condition .= " AND status>0";
		}
		else
		{
			$condition .= " AND status=0";
		}
	}
	#[计算信息数]
	$sql = "SELECT count(id) AS cid FROM ".$prefix."ad ".$condition;
	$m_count = $DB->qgGetOne($sql);
	$count = intval($m_count["cid"]);
	if(!$count || $count < 1)
	{
		Foot("phpok.qg");
		exit;
	}
	$sql = "SELECT id,subject,status,start_date,end_date FROM ".$prefix."ad ".$condition." ORDER BY id DESC LIMIT ".$offset.",".$psize;
	$adlist = $DB->qgGetAll($sql);
	$pagelist = page($page_url,$count,$psize,$pageid);#[获取页数信息]
}
Foot("phpok.qg");
?>