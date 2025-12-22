<?php
#[投票管理]
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["vote"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
if($sysAct == "add")
{
	$vote_array = array(0,1,2,3,4,5,6,7,8,9);#[创建数组]
}
elseif($sysAct == "addok")
{
	$subject = SafeHtml($subject);
	if(empty($subject))
	{
		Error("投票主题不能为空！","admin.php?file=vote&act=add");
	}
	$vtype = SafeHtml($vtype);
	#[写入主题]
	$DB->qgQuery("INSERT INTO ".$prefix."vote(subject,vtype,language) VALUES('".$subject."','".$vtype."','".$language."')");
	$id = $DB->qgInsertID();
	#[写入选项]
	$vote_array = array(0,1,2,3,4,5,6,7,8,9);#[创建数组]
	foreach($vote_array AS $key=>$value)
	{
		$vsubject = SafeHtml($_POST["subject_".$value]);
		$ifcheck = isset($_POST["ifcheck_".$value]) ? 1 : 0;
		$hits = intval($_POST["hits_".$value]);
		if($vsubject)
		{
			$sql = "INSERT INTO ".$prefix."vote(voteid,subject,vtype,vcount,language,ifcheck) VALUES('".$id."','".$vsubject."','".$vtype."','".$hits."','".$language."','".$ifcheck."')";
			$DB->qgQuery($sql);
		}
	}
	Error("新投票已经添加成功！","admin.php?file=vote&act=list");
}
elseif($sysAct == "modify")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=vote&act=list");
	}
	$vote_array = array(0,1,2,3,4,5,6,7,8,9);#[创建数组]
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."vote WHERE id='".$id."' AND voteid=0");
	if(!$rs)
	{
		Error("操作不正确，无法取得数据！","admin.php?file=vote&act=list");
	}
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."vote WHERE voteid='".$id."' ORDER BY id ASC");
}
elseif($sysAct == "modifyok")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=vote&act=list");
	}
	$subject = SafeHtml($subject);
	$vtype = SafeHtml($vtype);
	$DB->qgQuery("UPDATE ".$prefix."vote SET subject='".$subject."',vtype='".$vtype."' WHERE id='".$id."'");
	#[更新选项]
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."vote WHERE voteid='".$id."' ORDER BY id ASC");
	foreach($rslist AS $key=>$value)
	{
		$vsubject = SafeHtml($_POST["vsubject_".$value["id"]]);
		$vhits = intval($_POST["vhits_".$value["id"]]);
		$vifcheck = isset($_POST["vifcheck_".$value["id"]]) ? 1 : 0;
		if($vsubject)
		{
			$DB->qgQuery("UPDATE ".$prefix."vote SET subject='".$vsubject."',vtype='".$vtype."',vcount='".$vhits."',ifcheck='".$vifcheck."' WHERE id='".$value["id"]."'");
		}
		else
		{
			$DB->qgQuery("DELETE FROM ".$prefix."vote WHERE id='".$value["id"]."'");
		}
	}
	$vsubject ="";
	$vote_array = array(0,1,2,3,4,5,6,7,8,9);#[创建数组]
	foreach($vote_array AS $key=>$value)
	{
		$vsubject = SafeHtml($_POST["subject_".$value]);
		$ifcheck = isset($_POST["ifcheck_".$value]) ? 1 : 0;
		$hits = intval($_POST["hits_".$value]);
		if($vsubject)
		{
			$sql = "INSERT INTO ".$prefix."vote(voteid,subject,vtype,vcount,language,ifcheck) VALUES('".$id."','".$vsubject."','".$vtype."','".$hits."','".$language."','".$ifcheck."')";
			$DB->qgQuery($sql);
		}
	}
	Error("投票选项更新成功！","admin.php?file=vote&act=list");
}
elseif($sysact == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=vote&act=list");
	}
	$DB->qgQuery("DELETE FROM ".$prefix."vote WHERE id='".$id."'");
	$DB->qgQuery("DELETE FROM ".$prefix."vote WHERE voteid='".$id."'");
	Error("投票选项删除成功","admin.php?file=vote&act=list");
}
else
{
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."vote WHERE voteid=0 AND language='".$language."' ORDER BY id DESC LIMIT 0,500");
}
Foot("vote.qg");
?>