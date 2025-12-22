<?php
#[上传附件]
$UP->set_type("rar,zip,gz");#[限制附件类型上传]
function qgUpfilesDelete($id)
{
	global $DB,$prefix;
	global $FS;
	$obj = objReg();
	if(!$id || $id == "0")
	{
		$obj->addAlert("操作ID错误！");
		return $obj->getxml();
	}
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."upfiles WHERE id='".$id."'");
	if($rs)
	{
		if($rs["filename"])
		{
			$FS->qgDelete($rs["folder"].$rs["filename"]);
		}
	}
	$DB->qgQuery("DELETE FROM ".$prefix."upfiles WHERE id='".$id."'");
	$obj->addRemove("upfiles_".$id);#[删除显示]
	$obj->addAlert("附件：".$rs["tmpname"]." 删除成功！");
	return $obj->getxml();
}

$inputname = $_GET["inputname"] ? SafeHtml($_GET["inputname"]) : "content";
if($sysAct == "add")
{
	#[显示session里popenValue信息]
	$page_url = "admin.php?file=open.upfiles&act=add&inputname=".$inputname;
	$psize = 16;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	#[显示图片列表]
	#$condition = "WHERE filetype!='jpg' AND filetype!='gif' AND filetype!='png'";
	$condition = "";
	$get_count = $DB->qgCount("SELECT id FROM ".$prefix."upfiles ".$condition);
	$sql = "SELECT * FROM ".$prefix."upfiles ".$condition." ORDER BY postdate DESC LIMIT ".$offset.",".$psize;
	$rslist = $DB->qgGetAll($sql);
	$pagelist = page($page_url,$get_count,$psize,$pageid);#[获取页数信息]
	$filelist = array();
	foreach($rslist AS $key=>$value)
	{
		if(file_exists($value["folder"].$value["filename"]))
		{
			$filesize = filesize($value["folder"].$value["filename"]);
			if($filesize > 1024)
			{
				if($filesize > 1024 * 1024)
				{
					$value["filesize"] = round(($filesize/(1024*1024)),2) ." MB";
				}
				else
				{
					$value["filesize"] = round(($filesize/1024),2) ." KB";
				}
			}
			else
			{
				$value["filesize"] = $filesize." B";
			}
			$value["vTmpname"] = CutString($value["tmpname"],24);
			$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);#[上传时间]
			$filelist[] = $value;
		}
	}

	#[模板应用]
	$TPL->p("open.upfiles.qg");
	exit;
}
elseif($sysAct == "addok")
{
	#[写入数据库中]
	$tmpname = $UP->name("iframeUpload");#[客户端文件名]
	$setname = SafeHtml($setname);#[设置名称]
	$filename = $UP->up("iframeUpload",$system_time."_".rand(0,100));
	if(!$filename)
	{
		Error("没有上传附件...","admin.php?file=open.upfiles&act=add&inputname=".$inputname);
	}
	$extfile = substr($filename,-3);
	if(strpos(",zip,rar,.gz,",",".$extfile.",") === false)
	{
		if($filename)
		{
			$FS->qgDelete($mypath.$filename);
		}
		Error("附件类仅支持zip、rar和tar.gz三种格式，请不要上传其他类型的附件","admin.php?file=open.upfiles&act=add&inputname=".$inputname);
	}
	if($setname)
	{
		if($extfile == ".gz")
		{
			$tmpname = $setname.".tar.gz";
		}
		else
		{
			$tmpname = $setname.".".$extfile;
		}
	}
	#[获取当前服务器信息]
	$mypath = $UP->getpath();
	if($filename)
	{
		$filetype = $UP->qgfiletype($filename);
		#[上传的文件写入数据库中]
		$DB->qgQuery("INSERT INTO ".$prefix."upfiles(filetype,tmpname,filename,folder,postdate) VALUES('".$filetype."','".$tmpname."','".$filename."','".$mypath."','".$system_time."')");
	}
	Error("附件上传成功！","admin.php?file=open.upfiles&act=add&inputname=".$inputname);
	exit;
}
?>