<?php
#[招聘信息管理]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["job"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
if($sysAct == "add" || $sysAct == "modify")
{
	if($sysAct == "modify")
	{
		$id = intval($id);
		if(!$id)
		{
			Error("操作非法！","admin.php?file=job&act=list");
		}
		$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."job WHERE id='".$id."'");
		$rs["postdate"] = date("Y-m-d",$rs["postdate"]);
		$rs["enddate"] = ($rs["enddate"] && $rs["enddate"] != 0) ? date("Y-m-d",$rs["enddate"]) : 0;
	}
	else
	{
		$id = 0;
		$rs["jobname"] = $rs["content"] = "";
		$rs["postdate"] = date("Y-m-d",$system_time);
		$rs["enddate"] = 0;
	}
	Foot("job.qg");
}
elseif($sysAct == "modifyok")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=job&act=list");
	}
	$msg = $STR->safe($_POST);
	$msg["postdate"] = $msg["postdate"] ? strtotime($msg["postdate"]) : $system_time;
	$msg["enddate"] = $msg["enddate"] ? strtotime($msg["enddate"]) : 0;
	$sql = "UPDATE ".$prefix."job SET jobname='".$msg["jobname"]."',usercount='".$msg["usercount"]."',age='".$msg["age"]."',experience='".$msg["experience"]."',height='".$msg["height"]."',gender='".$msg["gender"]."',content='".$msg["content"]."',postdate='".$msg["postdate"]."',enddate='".$msg["enddate"]."' WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("招聘信息更新成功！","admin.php?file=job&act=list");
}
elseif($sysAct == "addok")
{
	$msg = $STR->safe($_POST);
	$msg["postdate"] = $msg["postdate"] ? strtotime($msg["postdate"]) : $system_time;
	$msg["enddate"] = $msg["enddate"] ? strtotime($msg["enddate"]) : 0;
	$sql = "INSERT INTO ".$prefix."job(jobname,usercount,age,experience,height,gender,content,postdate,enddate,language) VALUES('".$msg["jobname"]."','".$msg["usercount"]."','".$msg["age"]."','".$msg["experience"]."','".$msg["height"]."','".$msg["gender"]."','".$msg["content"]."','".$msg["postdate"]."','".$msg["enddate"]."','".$language."')";
	$DB->qgQuery($sql);
	Error("招聘信息添加成功！","admin.php?file=job&act=list");
}
elseif($sysAct == "applist")
{
	#[显示应聘信息]
	$psize = 10;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	$rscount = $DB->qgCount("SELECT id FROM ".$prefix."jobapp");
	$page_url = "admin.php?file=job&act=applist";
	$pagelist = page($page_url,$rscount,$psize,$pageid);#[获取页数信息]
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."jobapp ORDER BY postdate DESC,id DESC LIMIT ".$offset.",".$psize);
	$msglist = array();
	foreach($rslist AS $key=>$value)
	{
		$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);
		$value["photo"] = ($value["photo"] && file_exists("upfiles/jobapp/".$value["photo"])) ? "upfiles/jobapp/".$value["photo"] : "admin/tpl/images/nophoto.gif";
		$msglist[] = $value;
	}

	Foot("jobapp.qg");
}
elseif($sysact == "app_delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=job&act=applist");
	}
	$sql = "DELETE FROM ".$prefix."jobapp WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("应聘信息删除成功","admin.php?file=job&act=applist");
}
elseif($sysact == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=job&act=list");
	}
	$sql = "DELETE FROM ".$prefix."job WHERE id='".$id."'";
	$DB->qgQuery($sql);
	$sql = "DELETE FROM ".$prefix."jobapp WHERE jobid='".$id."'";
	$DB->qgQuery($sql);
	Error("删除招聘信息成功","admin.php?file=job&act=list");
}
else
{
	#[显示招聘列表]
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."job WHERE language='".$language."' ORDER BY postdate DESC,id DESC LIMIT 0,100");
	$msglist = array();
	foreach($rslist AS $key=>$value)
	{
		$value["postdate"] = date("Y-m-d",$value["postdate"]);
		$value["enddate"] = $value["enddate"] ? date("Y-m-d",$value["enddate"]) : "长期招聘";
		$value["gender"] = ($value["gender"] && $value["gender"] !=0) ? ($value["gender"] == 1 ? "男" : "女") : "不限";
		$msglist[] = $value;
	}
	Foot("job.qg");
}
?>