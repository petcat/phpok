<?php
#============================
#	Filename: include/job/apply.php
#	Note	: 在线申请表单
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-02-12
#============================
if(!defined("PHPOK_SET"))
{
	exit("Access Denied");
}

$act = $STR->safe($act);
if($act == "ok")
{
	$id = intval($id);
	$jobname = $STR->safe($jobname);
	$userid = $_SESSION["qg_sys_user"]["id"] ? $_SESSION["qg_sys_user"]["id"] : 0;
	$username = $STR->safe($username);
	$age = $STR->safe($age);
	$height = $STR->safe($height);
	$schoolage = $STR->safe($schoolage);
	$contact = $STR->safe($contact);
	if(!$jobname || !$username)
	{
		qgheader("job.php?file=apply&id=".$id);
	}
	#[加载上传照片功能]
	include_once("class/upload.class.php");
	$UP = new UPLOAD("upfiles/jobapp/","jpg,gif,png");
	$photo = $UP->up("photo");#[上传照片]
	$note = $STR->safe($note);
	$sql = "INSERT INTO ".$prefix."jobapp(jobid,userid,jobname,name,age,height,schoolage,contact,photo,note,postdate) VALUES('".$id."','".$userid."','".$jobname."','".$username."','".$age."','".$height."','".$schoolage."','".$contact."','".$photo."','".$note."','".$postdate."')";
	$DB->qgQuery($sql);
	Error($langs["job_ok"],"job.php?file=list");
}
else
{
	$id = intval($id);
	if($id)
	{
		$sql = "SELECT * FROM ".$prefix."job WHERE id='".$id."'";
		$rs = $DB->qgGetOne($sql);
	}
	#[标题头]
	$sitetitle = $langs["job_app"]." - ".$langs["job_list"]." - ".$system["sitename"];
	#[向导栏]
	$lead_menu[0]["url"] = "job.php?file=list";
	$lead_menu[0]["name"] = $langs["job_list"];
	$lead_menu[1]["url"] = "job.php?file=apply&id=".$id;
	$lead_menu[1]["name"] = $langs["job_app"];

	HEAD();
	FOOT("job_apply");
}
?>