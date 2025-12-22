<?php
#[定单管理]
$r_url = $_SESSION["return_url"] ? $_SESSION["return_url"] : "admin.php?file=order&act=list";
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["orderlist"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
if($sysact == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法",$r_url);
	}
	$DB->qgQuery("DELETE FROM ".$prefix."order WHERE id='".$id."'");
	Error("订单删除成功",$r_url);
}
elseif($sysact == "status")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法",$r_url);
	}
	$rs = $DB->qgGetOne("SELECT status FROM ".$prefix."order WHERE id='".$id."'");
	if($rs["status"])
	{
		$DB->qgQuery("UPDATE ".$prefix."order SET status='0' WHERE id='".$id."'");
		Error("订单设为未锁定状态成功！",$r_url);
	}
	else
	{
		$DB->qgQuery("UPDATE ".$prefix."order SET status='1' WHERE id='".$id."'");
		Error("订单设为已锁定状态成功！",$r_url);
	}
}
else
{
	$page_url = "admin.php?file=order&act=list";
	$psize = 15;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	$condition = "WHERE 1";
	$status = intval($status);
	if($status)
	{
		$page_url .= "&status=".$status;
		if($status == 1)
		{
			$condition .= " AND status='1'";
		}
		else
		{
			$condition .= " AND status='0'";
		}
	}
	$stype = SafeHtml($stype);#[搜索类型]
	$keywords = SafeHtml($keywords);
	if($keywords)
	{
		$page_url .= "&stype=".$stype."&keywords=".$keywords;
		$_SESSION["return_url"] = $page_url."&pageid=".$pageid;
		if($stype == "user")
		{
			$condition .= " AND user LIKE '%".$keywords."%'";
		}
		elseif($stype == "sn")
		{
			$condition .= " AND sn LIKE '%".$keywords."%'";
		}
		elseif($stype == "address")
		{
			$condition .= " AND address LIKE '%".$keywords."%'";
		}
		elseif($stype == "email")
		{
			$condition .= " AND email LIKE '%".$keywords."%'";
		}
		elseif($stype == "contact")
		{
			$condition .= " AND contactmode LIKE '%".$keywords."%'";
		}
		elseif($stype == "zipcode")
		{
			$condition .= " AND zipcode LIKE '%".$keywords."%'";
		}
		else
		{
			$condition .= " AND subject LIKE '%".$keywords."%'";
		}
	}
	$rcount = $DB->qgCount("SELECT id FROM ".$prefix."order ".$condition);#[获取商品总数]
	$pagelist = page($page_url,$rcount,$psize,$pageid);#[获取页数信息]
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."order ".$condition." ORDER BY postdate DESC,id DESC LIMIT ".$offset.",".$psize);
	$msglist = array();
	foreach($rslist AS $key=>$value)
	{
		$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);
		$msglist[] = $value;
	}
	unset($rslist);
	$_SESSION["return_url"] = $page_url."&pageid=".$pageid;
}
Foot("order.qg");
?>