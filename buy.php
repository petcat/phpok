<?php
#[购买产品]
require_once("global.php");
$act = SafeHtml($act);
$id = intval($id);
if($id)
{
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."msg WHERE id='".$id."'");
	if(!$rs)
	{
		Error($langs["buy_empty"],"home.php");
	}
}
if($act == "buyok")
{
	$buycount = intval($buycount);
	if(!$buycount || $buycount == 0)
	{
		Error($langs["buy_emptycount"],"buy.php?id=".$id);
	}
	if(!$id)
	{
		$productname = SafeHtml($subject);
		if(!$productname)
		{
			Error($langs["buy_emptypro"],"buy.php");
		}
	}
	else
	{
		$productname = $rs["subject"];
	}
	$email = SafeHtml($email);
	$username = SafeHtml($username);
	$contact = SafeHtml($contact);
	$address = SafeHtml($address);
	$zipcode = SafeHtml($zipcode);
	$note = SafeHtml($note);
	$userid = USER_STATUS == true ? $_SESSION["qg_sys_user"]["id"] : 0;
	#[生成订单号]
	$sn = $system_time."-".rand(1000,9999);#[订单号是10位数的 时间戳 + - + 四位随机数，具体可根自己需要进行个改]
	$DB->qgQuery("INSERT INTO ".$prefix."order(sn,userid,user,msgid,subject,msgcount,unitprice,totalprice,note,contactmode,address,zipcode,postdate,email,status) VALUES('".$sn."','".$userid."','".$username."','".$id."','".$productname."','".$buycount."','".$unitprice."','".$totalprice."','".$note."','".$contact."','".$address."','".$zipcode."','".$system_time."','".$email."','0')");
	#[判断是否需要发送邮件]
	$mailstatus = $DB->qgGetOne("SELECT * FROM ".$prefix."mailstatus WHERE language=".LANGUAGE_ID);
	if($mailstatus["status"])
	{
		if(!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$email))
		{
			Error($langs["buy_ok_error_email"],"home.php");
		}
		$my_siteurl = GetSystemUrl();
		$adminemail = $system["adminemail"] ? $system["adminemail"] : $system["mailqg"];
		$orderdate = date("Y-m-d H:i:s",$system_time);
		$var_array = array("{username}","{subject}","{postdate}","{ordersn}","{ordercount}","{unitprice}","{totalprice}","{siteurl}","{adminemail}","{sitename}");
		$new_array = array($username,$productname,$orderdate,$sn,$buycount,$unitprice,$totalprice,$my_siteurl,$adminemail,$system["sitename"]);
		$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."mailmsg WHERE language='".LANGUAGE_ID."'");
		if($rslist)
		{
			foreach($rslist AS $key=>$value)
			{
				$content = str_replace($var_array,$new_array,$value["content"]);
				if($value["usetype"] == "ordermsg_to_user")
				{
					if($mailstatus["sign"])
					{
						$content.= "<div>------------------------<br />".$mailstatus["sign"]."</div>";#[加入个性签名]
					}
					SendEmail($email,$value["subject"],$content);
				}
				else
				{
					if($adminemail)
					{
						SendEmail($adminemail,$value["subject"],$content);
					}
				}
			}
		}
	}
	Error($langs["buy_ok"],"home.php");
}
else
{
	#[标题头]
	if($id)
	{
		$sitetitle = $langs["buy_title"].$rs["subject"]." - ".$system["sitename"];
	}
	else
	{
		$sitetitle = $langs["buy_title2"]." - ".$system["sitename"];
	}
	#[向导栏]
	if($id)
	{
		$lead_menu[0]["name"] = $rs["subject"];
		$lead_menu[0]["url"] = "msg.php?id=".$id;
	}
	$lead_menu[1]["url"] = "buy.php?id=".$id;
	$lead_menu[1]["name"] = $langs["buy_title2"];
	HEAD();
	FOOT("buy");
}
?>