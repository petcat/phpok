<?php
#[会员个人信息修改]
$act = SafeHtml($act);
if($act == "setok")
{
	$password = SafeHtml($password);
	$chk_pass = SafeHtml($password2);
	$email = SafeHtml($email);
	$phone = SafeHtml($phone);
	$address = SafeHtml($address);
	$zipcode = SafeHtml($zipcode);
	#[检测密码]
	if(!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$email))
	{
		Error($langs["my_email_wrong"],"my.php?file=usercp");
	}
	$sql = "UPDATE ".$prefix."user SET email='".$email."'";
	if($password)
	{
		if($password != $chk_pass)
		{
			Error($langs["pass_not_same"],"my.php?file=usercp");
		}
		else
		{
			$mypass = md5($password);
			$sql .= ",pass='".$mypass."'";
		}
	}
	$sql .= ",phone='".$phone."',address='".$address."',postmail='".$zipcode."' WHERE id='".$_SESSION["qg_sys_user"]["id"]."'";
	$DB->qgQuery($sql);
	#[更新会员缓存信息]
	$sql = "SELECT * FROM ".$prefix."user WHERE id='".$_SESSION["qg_sys_user"]["id"]."'";
	$usermsg = $DB->qgGetOne($sql);
	Error($langs["my_updateok"],"my.php?file=usercp");
}
else
{
	$usermsg = $DB->qgGetOne("SELECT * FROM ".$prefix."user WHERE id='".$_SESSION["qg_sys_user"]["id"]."'");
	#[标题头]
	$sitetitle = $langs["my_update_msg"]." - ".$langs["my_usercp"]." - ".$system["sitename"];
	#[向导栏]
	$lead_menu[0]["url"] = "my.php?file=usercp";
	$lead_menu[0]["name"] = $langs["my_usercp"];
	$lead_menu[1]["url"] = "my.php?file=usercp";
	$lead_menu[1]["name"] = $langs["my_update_msg"];
	HEAD();
	FOOT("my_usercp");
}
?>