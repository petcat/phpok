<?php
#============================
#	Filename: include/job.list.php
#	Note	: 招聘列表
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-03-26
#============================
$joblist = $DB->qgGetAll("SELECT * FROM ".$prefix."job ORDER BY postdate DESC,id DESC");

#[标题头]
$sitetitle = $langs["job_list"]." - ".$system["sitename"];
#[向导栏]
$lead_menu[0]["url"] = "job.php?file=list";
$lead_menu[0]["name"] = $langs["job_list"];

HEAD();
FOOT("job_list");
?>