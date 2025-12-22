<?php
//语言设置管理
if($_SESSION["admin"]["typer"] != "system")
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
function UpdateLanguageCache($id="")
{
	global $DB,$FS,$prefix;
	if($id)
	{
		$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."lang WHERE id='".$id."'");
		$langc = $write_array = array();
		$langc = explode("\n",$rs["langc"]);
		foreach($langc AS $key=>$value)
		{
			$value = trim($value);
			if($value)
			{
				$v = explode("{,}",$value);
				$v[1] = str_replace("[:space:]","&nbsp;",$v[1]);
				$write_array[$v[0]] = $v[1];
			}
		}
		$FS->qgWrite($write_array,"langs/".$rs["sign"].".php","langs");
	}
	else
	{
		$rs = $DB->qgGetAll("SELECT id FROM ".$prefix."lang");
		foreach($rs AS $key=>$value)
		{
			UpdateLanguageFile($value["id"]);
		}
	}
	return true;
}

if($sysact == "addok")
{
	$name = $STR->safe($name);
	if(!$name)
	{
		Error("语言名称不能为空！","admin.php?file=language&act=add");
	}
	$sign = $STR->safe($sign);
	$status = intval($status);
	$content = $STR->safe($content);
	$rs = $DB->qgGetOne("SELECT id FROM ".$prefix."lang WHERE sign='".$sign."'");
	if($rs)
	{
		Error("语言标识符已存在","admin.php?file=language&act=add");
	}
	$sql = "INSERT INTO ".$prefix."lang(sign,name,langc,ifuse) VALUES('".$sign."','".$name."','".$content."','".$status."')";
	$id = $DB->qgInsert($sql);
	UpdateLanguageCache($id);#[更新语言包缓存]
	Error("成功添加一个语言包","admin.php?file=language&act=add");
}
elseif($sysact == "status")
{
	$id = intval($id);
	chk_id($id,"admin.php?file=language&act=list");
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."lang WHERE id='".$id."'");
	if($rs["ifuse"] == 1)
	{
		$DB->qgQuery("UPDATE ".$prefix."lang SET ifuse='0' WHERE id='".$id."'");
		Error("语言：".$rs["name"]." 设置为停用操作成功！","admin.php?file=language&act=list");
	}
	else
	{
		$DB->qgQuery("UPDATE ".$prefix."lang SET ifuse='1' WHERE id='".$id."'");
		Error("语言：".$rs["name"]." 设置为使用操作成功！","admin.php?file=language&act=list");
	}
}
elseif($sysact == "delete")
{
	$id = intval($id);
	chk_id($id,"admin.php?file=language&act=list");
	#[检测语言ID是否在使用]
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."lang WHERE id='".$id."'");
	if($rs["ifuse"] || $rs["ifdefault"])
	{
		Error("正在使用或默认使用的语言包不允许删除！","admin.php?file=language&act=list");
	}
	if($rs["ifsystem"])
	{
		Error("系统默认语言包不允许删除","admin.php?file=language&act=list");
	}
	$sql = "DELETE FROM ".$prefix."lang WHERE ifuse='0' AND ifdefault='0' AND ifsystem='0' AND id='".$id."'";
	$DB->qgQuery($sql);
	Error("语言包删除成功","admin.php?file=language&act=list");
}
elseif($sysact == "setdefault")
{
	$id = intval($id);
	chk_id($id,"admin.php?file=language&act=list");
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."lang WHERE id='".$id."'");
	$DB->qgQuery("UPDATE ".$prefix."lang SET ifdefault='0'");
	$DB->qgQuery("UPDATE ".$prefix."lang SET ifdefault='1' WHERE id='".$id."'");
	Error("设置语言 ".$rs["name"]." 为默认语言！","admin.php?file=language&act=list");
}
elseif($sysAct == "list")
{
	$rslist = $DB->qgGetAll("SELECT id,sign,name,ifuse,ifdefault,ifsystem FROM ".$prefix."lang ORDER BY id DESC");
}
elseif($sysAct == "modify")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=language&act=list");
	}
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."lang WHERE id='".$id."'");
}
elseif($sysAct == "modifyok")
{
	$id = intval($id);
	if(!$id)
	{
		Error("操作非法！","admin.php?file=language&act=list");
	}
	$name = $STR->safe($name);
	if(!$name)
	{
		Error("语言名称不能为空！","admin.php?file=language&act=modify&id=".$id);
	}
	$status = intval($status);
	$content = $STR->safe($content);
	$DB->qgQuery("UPDATE ".$prefix."lang SET name='".$name."',langc='".$content."',ifuse='".$status."' WHERE id='".$id."'");
	UpdateLanguageCache($id);#[更新语言包缓存]
	Error("语言包信息更新成功","admin.php?file=language&act=list");
}
Foot("language.qg");
?>