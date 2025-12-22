<?php
#[上传图片]
$system_url = GetSystemUrl();
$inputname = $_GET["inputname"] ? SafeHtml($_GET["inputname"]) : "content";
if($sysAct == "add")
{
	#[显示session里popenValue信息]
	$page_url = "admin.php?file=open.addpic&act=add&inputname=".$inputname;
	$psize = 20;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	#[显示图片列表]
	$sql_count = "SELECT count(id) FROM ".$prefix."upfiles WHERE filetype IN('jpg','gif','png')";
	$get_count = $DB->qgCount($sql_count);
	unset($sql_count);
	$sql = "SELECT * FROM ".$prefix."upfiles WHERE filetype IN('jpg','gif','png') ORDER BY postdate DESC LIMIT ".$offset.",".$psize;
	$rslist = $DB->qgGetAll($sql);
	unset($sql);
	foreach($rslist AS $key=>$value)
	{
		$m = array();
		if(file_exists($value["folder"].$value["filename"]))
		{
			if(file_exists($value["folder"].$value["thumbfile"]))
			{
				$value["show_pic"] = $value["folder"].$value["thumbfile"];
				$m[0] = $system_url.$value["folder"].$value["thumbfile"];
			}
			else
			{
				$vlaue["show_pic"] = $value["folder"].$value["filename"];
				$m[0] = $system_url.$value["folder"].$value["filename"];
			}
			#[水印图数组]
			if(file_exists($value["folder"].$value["markfile"]))
			{
				$m[1] = $system_url.$value["folder"].$value["markfile"];
			}
			else
			{
				$m[1] = $system_url.$value["folder"].$value["filename"];
			}
			#[大图]
			$m[2] = $system_url.$value["folder"].$value["filename"];
			$value["input_message"] = implode("|,|",$m);
			$piclist[] = $value;
		}
	}
	$pagelist = page($page_url,$get_count,$psize,$pageid);#[获取页数信息]
	#[模板应用]
	$TPL->p("open.addpic.qg");
	exit;
}
elseif($sysAct == "addok")
{
	#[写入数据库中]
	$tmpname = $UP->name("iframeUpload");#[客户端文件名]
	$filename = $UP->up("iframeUpload",$system_time."_".rand(0,100));
	if(!$filename)
	{
		Error("没有选择上传图片...","admin.php?file=open.addpic&act=add&inputname=".$inputname);
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
	Error("图片上传成功！","admin.php?file=open.addpic&act=add&inputname=".$inputname);
}
?>