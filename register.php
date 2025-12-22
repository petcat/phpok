<?php
#[会员注册]
require_once("global.php");
if($act == "regok")
{
	$username = SafeHtml($username);
	if(!$username)
	{
		Error($langs["reg_emptyuser"],"register.php");
	}
	$password = SafeHtml($password);
	$chkpass = SafeHtml($checkpass);
	if(!$password)
	{
		Error($langs["reg_emptypass"],"register.php");
	}
	if(!$chkpass)
	{
		Error($langs["reg_emptychkpass"],"register.php");
	}
	if($password != $chkpass)
	{
		Error($langs["reg_difpass"],"register.php");
	}
	$email = SafeHtml($email);
	if(!$email)
	{
		Error($langs["reg_emptyemail"],"register.php");
	}
	if(!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$email))
	{
		Error($langs["reg_erroremail"],"register.php");
	}
	$check_user = $DB->qgGetOne("SELECT * FROM ".$prefix."user WHERE user='".$username."'");
	if($check_user)
	{
		Error($langs["reg_user_exist"],"register.php");
	}
	$password = md5($password);
	$id = $DB->qgInsert("INSERT INTO ".$prefix."user(user,nickname,realname,pass,email,regdate) VALUES('".$username."','".$username."','','".$password."','".$email."','".$system_time."')");
	$id = $DB->qgInsertID();
	#[直接登录]
	$_SESSION["qg_sys_user"]["id"] = $id;
	$_SESSION["qg_sys_user"]["user"] = $username;
	$_SESSION["qg_sys_user"]["pass"] = $password;
	$_SESSION["qg_sys_user"]["email"] = $email;
	Error($langs["reg_ok"],"home.php");
}
else
{
	if(USER_STATUS == true)
	{
		Error($langs["reg_disabled"],"home.php");
	}
	#[标题头]
	$sitetitle = $langs["reg_title"]." - ".$system["sitename"];
	#[向导栏]
	$lead_menu[0]["url"] = "register.php";
	$lead_menu[0]["name"] = $langs["reg_title"];
	HEAD();
	FOOT("register");
}
?>