<?php
#[邮件发送设置]
$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."mailstatus WHERE language='".$language."'");
#[判断是否已设置好SMTP]
$mailserver = false;
if($system["mailhost"] && $system["mailqg"])
{
	$mailserver = true;
}
if($sysAct == "status")
{
	if($_SESSION["admin"]["typer"] != "system")
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
}
elseif($sysact == "statusok")
{
	if($_SESSION["admin"]["typer"] != "system")
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$status = intval($status);
	$sign = $STR->safe($sign);
	if($rs)
	{
		$sql = "UPDATE ".$prefix."mailstatus SET status='".$status."',sign='".$sign."' WHERE id='".$rs["id"]."'";
	}
	else
	{
		$sql = "INSERT INTO ".$prefix."msg(status,sign,language) VALUES('".$status."','".$sign."','".$language."')";
	}
	$DB->qgQuery($sql);
	Error("更新设置成功...","admin.php?file=email&act=status");
}
elseif($sysAct == "msg")
{
	if($_SESSION["admin"]["typer"] != "system")
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$usetype = $STR->safe($usetype);
	if($usetype)
	{
		$msg = $DB->qgGetOne("SELECT * FROM ".$prefix."mailmsg WHERE language='".$language."' AND usetype='".$usetype."'");
		$content = FckToHtml($msg["content"]);
		$fckeditor = FckEditor("content",$content,"LongDefault","400px","100%");
	}
}
elseif($sysact == "msgok")
{
	if($_SESSION["admin"]["typer"] != "system")
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$usetype = $STR->safe($usetype);
	if(!$usetype)
	{
		Error("操作非法...","admin.php?file=email&act=msg");
	}
	$subject = $STR->safe($subject);
	$content = $STR->html($content);
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."mailmsg WHERE language='".$language."' AND usetype='".$usetype."'");
	if($rs)
	{
		$sql = "UPDATE ".$prefix."mailmsg SET subject='".$subject."',content='".$content."' WHERE id='".$rs["id"]."'";
	}
	else
	{
		$sql = "INSERT INTO ".$prefix."mailmsg(language,subject,content,usetype) VALUES('".$language."','".$subject."','".$content."','".$usetype."')";
	}
	$DB->qgQuery($sql);
	Error("数据更新成功","admin.php?file=email&act=msg&usetype=".$usetype);
}
elseif($sysAct == "send")
{
	$email = $STR->safe($email);
	$fckeditor = FckEditor("content","","LongDefault","400px","100%");
}
elseif($sysAct == "sendok")
{
	$touser = $STR->html($touser);
	$subject = $STR->safe($subject);
	$content = $STR->html($content);
	$msg = stripslashes($msg);#
	$content = FckToHtml($content);
	#[判断是否有会员]
	if(!$touser)
	{
		Error("邮箱不能为空！","admin.php?file=email&act=send");
	}
	if(!$subject)
	{
		Error("主题不能为空！","admin.php?file=email&act=send&email=".$touser);
	}
	if(!$content)
	{
		Error("内容不能为空！","admin.php?file=email&act=send&email=".$touser);
	}
	include_once("./class/phpmailer.class.php");
	$SML = new PHPMailer();
	$SML->CharSet = $system["mailtype"] ? $system["mailtype"] : "utf8";
	if($SML->CharSet == "gbk")
	{
		$subject = Utf2gb($subject);
		$content = Utf2gb($content);
	}
	$SML->IsSMTP();
	$SML->Host = trim($system["mailhost"]);
	$SML->Port = trim($system["mailport"]) ? trim($system["mailport"]) : 25;
	$SML->SMTPAuth = true;
	$SML->Username = trim($system["mailuser"]);
	$SML->Password = trim($system["mailpass"]);

	$SML->From = trim($system["mailqg"]);
	$SML->FromName = trim($system["mailuser"]);
	#[回复地址]
	if($system["adminemail"])
	{
		$SML->AddReplyTo($system["adminemail"]);
	}
	else
	{
		$SML->AddReplyTo($system["mailqg"]);
	}
	$SML->IsHTML(true);

	$e_array = array();
	$e_array = explode(",",$touser);
	if(count($e_array)>1)
	{
		foreach($e_array AS $key=>$value)
		{
			$value = trim($value);
			if($value)
			{
				$SML->AddAddress($value);
			}
		}
	}
	else
	{
		$SML->AddAddress($touser);
	}
	$SML->Subject = $subject;
	$SML->Body = $content;
	if($SML->Send())
	{
		Error("邮件发送成功！","admin.php?file=email&act=send&email=".$touser);
	}
	else
	{
		Error("邮件发送失败！请检查配置！","admin.php?file=email&act=send&email=".$touser);
	}
}
Foot("email.qg");
?>