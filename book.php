<?php
#[留言本]
require_once("global.php");
if($act == "addok")
{
	$subject = $STR->safe($subject);
	$content = $STR->safe($content);
	if(!$subject)
	{
		Error($langs["book_subjectempty"],"book.php?act=add");
	}
	if(!$content)
	{
		Error($langs["book_msgempty"],"book.php?act=add");
	}
	$typeid = intval($typeid);
	if(USER_STATUS == true)
	{
		$userid = $_SESSION["qg_sys_user"]["id"];
		$user_name = $_SESSION["qg_sys_user"]["user"];
		$email = $_SESSION["qg_sys_user"]["email"];
	}
	else
	{
		$userid = 0;
		$user_name = $STR->safe($qgusername);
		$email = $STR->safe($email);
		if(!$user_name)
		{
			Error($langs["book_nouser"],"book.php?act=add");
		}
		if(!$email)
		{
			Error($langs["book_noemail"],"book.php?act=add");
		}
		if(!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$email))
		{
			Error($langs["book_notemail"],"book.php?act=add");
		}
	}
	#[是否审核]
	$ifcheck = 0;#[设为未审核，只能管理员在后台设置审核后前台才能看到]
	#[入库]
	$DB->qgQuery("INSERT INTO ".$prefix."book(userid,user,subject,content,postdate,email,ifcheck,language) VALUES('".$userid."','".$user_name."','".$subject."','".$content."','".$system_time."','".$email."','".$ifcheck."','".LANGUAGE_ID."')");
	Error($langs["book_sendok"].$msg,"book.php?act=list");
}
else
{
	$count = $DB->qg_count("SELECT count(id) FROM ".$prefix."book WHERE ifcheck=1 AND language='".LANGUAGE_ID."'");
	$url = "book.php?act=list";
	$psize = 15;
	$pageid = intval($pageid);
	$pagelist = page($url,$count,$psize,$pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	$rs = $DB->qgGetAll("SELECT * FROM ".$prefix."book WHERE ifcheck=1 AND language='".LANGUAGE_ID."' ORDER BY postdate DESC,id DESC LIMIT ".$offset.",".$psize);
	$book_list = array();
	foreach($rs AS $key=>$value)
	{
		$value["postdate"] = date("Y-m-d",$value["postdate"]);
		$value["altSubject"] = $value["subject"];
		$value["subject"] = CutString($value["subject"],30);
		$value["content"] = preg_replace("/<style(.*?)<\/style>/is","",$value["content"]);
		$value["content"] = preg_replace("/<(.*?)>/is","",$value["content"]);
		$book_list[] = $value;
	}
	#[标题头]
	$sitetitle = $langs["bookname"]." - ".$system["sitename"];
	#[向导栏]
	$lead_menu[0]["url"] = "book.php";
	$lead_menu[0]["name"] = $langs["bookname"];
}
HEAD();
FOOT("book");
?>