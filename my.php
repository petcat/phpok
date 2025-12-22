<?php
#[我的控制面板]
require_once("global.php");
#[判断是否是会员]
if(!USER_STATUS)
{
	qgheader();
}
$incfile = $STR->safe($file);
if(file_exists("include/usercp/".$incfile.".php"))
{
	include_once("include/usercp/".$incfile.".php");
}
else
{
	qgheader();
}
?>