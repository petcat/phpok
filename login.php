<?php
#[会员登录页]
require_once("global.php");
if($act == "loginok")
{
	$username = $username; // 已在global.php中处理
	$password = $password; // 已在global.php中处理
	
	if(!$username)
	{
		Error($langs["empty_user"],"login.php");
	}
	if(!$password)
	{
		Error($langs["empty_pass"],"login.php");
	}
	
	#[使用预处理语句防止SQL注入]
	$rs = $DB->qgGetOne("SELECT id,user,pass,email FROM ".$prefix."user WHERE user=? AND status=1", [$username]);
	if(!$rs)
	{
		Error($langs["notuser"],"login.php");
	}
	
	#[验证密码 - 使用更安全的密码验证]
	$stored_password = $rs['pass'];
	# 检查是否是旧的MD5密码格式
	if (strlen($stored_password) == 32 && ctype_xdigit($stored_password)) {
		# 旧的MD5格式，验证后升级到更安全的格式
		if($stored_password === md5($password)) {
			# 验证成功，升级密码到更安全的格式
			$new_hash = password_hash($password, PASSWORD_DEFAULT);
			$DB->qgExec("UPDATE ".$prefix."user SET pass=? WHERE id=?", [$new_hash, $rs['id']]);
			$_SESSION["qg_sys_user"] = $rs;
		} else {
			Error($langs["notuser"],"login.php");
		}
	} else {
		# 新的密码哈希格式，使用password_verify验证
		if(password_verify($password, $stored_password)) {
			$_SESSION["qg_sys_user"] = $rs;
		} else {
			Error($langs["notuser"],"login.php");
		}
	}
	
	#[指定跳转页]
	if($_SESSION["refresh_url"])
	{
		qgheader($_SESSION["refresh_url"]);
	}
	else
	{
		qgheader();
	}
}
elseif($act == "logout")
{
	$_SESSION["qg_sys_user"] = "";
	qgheader();
}
else
{
	if($_SESSION["qg_sys_user"])
	{
		qgheader();
	}
	#[标题头]
	$sitetitle = $langs["logintitle"]." - ".$system["sitename"];
	#[向导栏]
	$lead_menu[0]["url"] = $siteurl."login.php";
	$lead_menu[0]["name"] = $langs["logintitle"];
	HEAD();
	FOOT("login");
}
?>