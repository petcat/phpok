<?php
$sysact = $STR->safe($act);
if($sysact == "addok")
{
	$subject = $STR->safe($subject);
	$content = $STR->safe($content);
	if(!$subject || !$content)
	{
		Error($langs["my_fb_subject_content_empty"],"my.php?file=feedback");
	}
	$sql = "INSERT INTO ".$prefix."feedback(userid,subject,content,postdate) VALUES('".$_SESSION["qg_sys_user"]["id"]."','".$subject."','".$content."','".$system_time."')";
	$insert_id = $DB->qgInsert($sql);
	if($insert_id)
	{
		$tishi_msg = $langs["my_fb_sendok"];
	}
	else
	{
		$tishi_msg = $langs["my_fb_sendfail"];
	}
	Error($tishi_msg,"my.php?file=feedback");
}
elseif($sysact == "delete")
{
	$id = intval($id);
	if(!$id)
	{
		qgheader("my.php?file=feedback");
	}
	$DB->qgQuery("DELETE FROM ".$prefix."feedback WHERE id='".$id."' AND userid='".$_SESSION["qg_sys_user"]["id"]."'");
	Error($langs["my_fb_deleteok"],"my.php?file=feedback");
}
else
{
	#[反馈信息列表]
	$psize = 10;#[每页显示10个反馈信息]
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	$pageurl = "my.php?file=feedback";
	$rscount = $DB->qg_count("SELECT count(id) FROM ".$prefix."feedback WHERE userid='".$_SESSION["qg_sys_user"]["id"]."'");
	$pagelist = page($pageurl,$rscount,$psize,$pageid);#[获取分页的数组]
	$rslist = $DB->qgGetAll("SELECT * FROM ".$prefix."feedback WHERE userid='".$_SESSION["qg_sys_user"]["id"]."' ORDER BY postdate DESC,id DESC LIMIT ".$offset.",".$psize);
	$feedback_list = array();
	foreach($rslist AS $key=>$value)
	{
		$value["content"] = nl2br($value["content"]);
		$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);
		$feedback_list[] = $value;
	}
	unset($rslist);
	#[标题头]
	$sitetitle = $langs["my_feedback"]." - ".$langs["my_usercp"]." - ".$system["sitename"];
	#[向导栏]
	$lead_menu[0]["url"] = "my.php?file=usercp";
	$lead_menu[0]["name"] = $langs["my_usercp"];
	$lead_menu[1]["url"] = "my.php?file=feedback";
	$lead_menu[1]["name"] = $langs["my_feedback"];
	HEAD();
	FOOT("my_feedback");
}
?>