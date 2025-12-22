<?php
#[上传图片]
$upfolder = "upfiles";

$inputname = $_GET["id"] ? SafeHtml($_GET["id"]) : "content";
if($sysAct == "add" || $sysAct == "list")
{
	$page_url = "admin.php?file=open.index.img&act=add&id=".$inputname;
	$psize = 20;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	#[显示图片列表]
	$condition = "WHERE 1 AND filetype IN('jpg','gif','png')";
	$get_count = $DB->qgCount("SELECT id FROM ".$prefix."upfiles ".$condition);
	$sql = "SELECT * FROM ".$prefix."upfiles ".$condition." ORDER BY postdate DESC LIMIT ".$offset.",".$psize;
	$rslist = $DB->qgGetAll($sql);
	$msglist = array();
	foreach($rslist AS $key=>$value)
	{
		if(file_exists($value["folder"].$value["thumbfile"]))
		{
			$value["show_pic"] = $value["folder"].$value["thumbfile"];
		}
		else
		{
			$vlaue["show_pic"] = $value["folder"].$value["filename"];
		}
		$msglist[] = $value;
	}
	$pagelist = page($page_url,$get_count,$psize,$pageid);#[获取页数信息]
	#[模板应用]
	$TPL->p("open.index.img.qg");
	exit;
}
elseif($sysAct == "addok")
{
	#[写入数据库中]
	$tmpname = $UP->name("iframeUpload");#[客户端文件名]
	$filename = $UP->up("iframeUpload",$system_time."_".rand(0,100));
	if(!$filename)
	{
		Error("没有选择上传的图片...","admin.php?file=open.index.img&act=add&id=".$inputname);
	}
	if(strpos(",jpg,gif,png,",",".substr($filename,-3).",") !== false)
	{
		#[获取当前服务器信息]
		$mypath = $UP->getpath();
		#[生成缩略图]
		$thumbfile = $GD->thumb($mypath.$filename);
		#[生成水印图]
		$markfile = $GD->mark($mypath.$filename);
	}
	if($filename)
	{
		$filetype = $UP->qgfiletype($filename);
		#[上传的文件写入数据库中]
		$DB->qgQuery("INSERT INTO ".$prefix."upfiles(filetype,tmpname,filename,folder,postdate,thumbfile,markfile) VALUES('".$filetype."','".$tmpname."','".$filename."','".$mypath."','".$system_time."','".$thumbfile."','".$markfile."')");
	}
	Error("图片上传成功！","admin.php?file=open.index.img&act=add&id=".$inputname);
}
?>