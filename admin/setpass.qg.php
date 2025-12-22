<?php
$return_url = "admin.php?file=setpass";
if($sysact == "setpassok")
{
	$old = $STR->safe($old);
	$new = $STR->safe($new);
	$chk = $STR->safe($chk);
	if(!$old || !$new || !$chk)
	{
		Error("所有加星号信息均必须填写",$return_url);
	}
	if($old == $new)
	{
		Error("新旧密码是一致的！不需要修改",$return_url);
	}
	if($new != $chk)
	{
		Error("不允许修改，两次密码输入不一致",$return_url);
	}
	$sql = "UPDATE ".$prefix."admin SET pass='".md5($new)."' WHERE id='".$_SESSION["admin"]["id"]."'";
	$DB->qgQuery($sql);
	$sql = "SELECT * FROM ".$prefix."admin WHERE id='".$_SESSION["admin"]["id"]."'";
	$rs = $DB->qgGetOne($sql);
	$_SESSION["admin"] = $rs;
	Error("密码更新成功！下次登录请使用新密码",$return_url);
}
Foot("setpass.qg");
?>