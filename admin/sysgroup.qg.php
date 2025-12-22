<?php
$r_url = "admin.php?file=sysgroup";
#[判断权限]
if($_SESSION["admin"]["typer"] != "system")
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
#[系统组管理]
if($sysact == "add")
{
	Foot("sysgroup.add.qg");
}
elseif($sysact == "addok")
{
	$msg = $STR->safe($_POST);
	if(!$msg["groupname"])
	{
		Error("组名称不能为空",$r_url."&act=add");
	}
	if(!$msg["sign"])
	{
		Error("标识符不能为空",$r_url."&act=add");
	}
	#[检测组名称是否存在]
	$sql = "SELECT groupname FROM ".$prefix."sysgroup WHERE groupname='".$msg["groupname"]."'";
	$chkgroup = $DB->qgGetOne($sql);
	if($chkgroup)
	{
		Error("组名称已经被使用",$r_url."&act=add");
	}
	#[检测组标识是否存在]
	$sql = "SELECT sign FROM ".$prefix."sysgroup WHERE sign='".$msg["sign"]."'";
	$chksign = $DB->qgGetOne($sql);
	if($chksign)
	{
		Error("组标识已经被使用",$r_url."&act=add");
	}
	$sql = "INSERT INTO ".$prefix."sysgroup(groupname,isext_url,isext_docket,isext_price,isext_standard,";
	$sql.= "isext_number,isext_size,isext_download,ext_0_name,ext_1_name,ext_2_name,ext_3_name,ext_4_name,ext_5_name,";
	$sql.= "ext_6_name,ext_7_name,ext_8_name,ext_9_name,sign,tplindex,tpllist,tplmsg,is_thumb) VALUES";
	$sql.= "('".$msg["groupname"]."','".$msg["isext_url"]."','".$msg["isext_docket"]."','".$msg["isext_price"]."',";
	$sql.= "'".$msg["isext_standard"]."','".$msg["isext_number"]."','".$msg["isext_size"]."','".$msg["isext_download"]."',";
	$sql.= "'".$msg["ext_0_name"]."','".$msg["ext_1_name"]."','".$msg["ext_2_name"]."','".$msg["ext_3_name"]."',";
	$sql.= "'".$msg["ext_4_name"]."','".$msg["ext_5_name"]."','".$msg["ext_6_name"]."','".$msg["ext_7_name"]."',";
	$sql.= "'".$msg["ext_8_name"]."','".$msg["ext_9_name"]."','".$msg["sign"]."','".$msg["tplindex"]."','".$msg["tpllist"]."',";
	$sql.= "'".$msg["tplmsg"]."','".$msg["is_thumb"]."')";
	$DB->qgQuery($sql);
	Error("系统组创建成功！",$r_url."&act=list");
}
elseif($sysact == "list")
{
	#[显示列表]
	$rslist = $DB->qgGetAll("SELECT id,groupname FROM ".$prefix."sysgroup LIMIT 0,100");
	Foot("sysgroup.list.qg");
}
elseif($sysact == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法",$r_url."&act=list");
	}
	$sql = "DELETE FROM ".$prefix."sysgroup WHERE id='".$id."'";
	$DB->qgQuery($sql);
	$sql = "UPDATE ".$prefix."category SET sysgroupid='0' WHERE sysgroupid='".$id."'";
	$DB->qgQuery($sql);
	Error("组信息已删除，原组内容已清空，请记得清空相关缓存",$r_url."&act=list");
}
elseif($sysact == "modify")
{
	$id = intval($id);
	if(!$id)
	{
		Error("无法获取的ID号信息","admin.php?file=sysgroup&act=list");
	}
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."sysgroup WHERE id='".$id."'");
	Foot("sysgroup.modify.qg");
}
elseif($sysAct == "modifyok")
{
	$id = intval($id);
	if(!$id)
	{
		Error("无法获取的ID号信息",$r_url."&act=list");
	}
	$msg = $STR->safe($_POST);
	if(!$msg["sign"])
	{
		Error("标识符不能为空",$r_url."&act=modify&id=".$id);
	}
	$sql = "SELECT sign FROM ".$prefix."sysgroup WHERE sign='".$msg["sign"]."' AND id!='".$id."'";
	$chksign = $DB->qgGetOne($sql);
	if($chksign)
	{
		Error("组标识已经被使用",$r_url."&act=modify&id=".$id);
	}
	$sql = "UPDATE ".$prefix."sysgroup SET isext_url='".$msg["isext_url"]."',isext_docket='".$msg["isext_docket"]."',";
	$sql.= "isext_price='".$msg["isext_price"]."',isext_standard='".$msg["isext_standard"]."',isext_number='".$msg["isext_number"]."',";
	$sql.= "isext_size='".$msg["isext_size"]."',isext_download='".$msg["isext_download"]."',ext_0_name='".$msg["ext_0_name"]."',";
	$sql.= "ext_1_name='".$msg["ext_1_name"]."',ext_2_name='".$msg["ext_2_name"]."',ext_3_name='".$msg["ext_3_name"]."',";
	$sql.= "ext_4_name='".$msg["ext_4_name"]."',ext_5_name='".$msg["ext_5_name"]."',ext_6_name='".$msg["ext_6_name"]."',";
	$sql.= "ext_7_name='".$msg["ext_7_name"]."',ext_8_name='".$msg["ext_8_name"]."',ext_9_name='".$msg["ext_9_name"]."',";
	$sql.= "sign='".$msg["sign"]."',tplindex='".$msg["tplindex"]."',tpllist='".$msg["tpllist"]."',tplmsg='".$msg["tplmsg"]."',";
	$sql.= "is_thumb='".$msg["is_thumb"]."' WHERE id='".$id."'";
	$DB->qgQuery($sql);
	Error("系统组信息编辑成功...","admin.php?file=sysgroup&act=list");
}
?>