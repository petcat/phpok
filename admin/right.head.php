<?php
#[获取语言信息]
$language = $_SESSION["language"];
if($langid)
{
	$language = $langid;
	$_SESSION["language"] = $langid;
}
$langArray = $DB->qgGetAll("SELECT id,name FROM ".$prefix."lang WHERE ifuse=1");
foreach($langArray AS $key=>$value)
{
	$value["select"] = $value["id"] == $language ? " selected" : "";
	$lang_array[] = $value;
}
unset($langArray);
#[判断管理员类型]
$ADMIN_TYPE_LIST = array("system"=>"系统管理员","manager"=>"一般管理员","editor"=>"工作人员");
$ADMIN_TYPER = $ADMIN_TYPE_LIST[$_SESSION["admin"]["typer"]];
if(!$ADMIN_TYPER)
{
	$ADMIN_TYPER = "工作人员";
}
?>