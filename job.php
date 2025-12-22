<?php
#============================
#	Filename: job.php
#	Note	: 招聘应聘信息
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-03-24
#============================
require_once("global.php");
#[填写表单]
$jobfile = $STR->safe($file);
if(!$jobfile)
{
	$jobfile = "list";
}
if(file_exists("include/job/".$jobfile.".php"))
{
	include_once("include/job/".$jobfile.".php");
}
else
{
	qgheader();
}
?>