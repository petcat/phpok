<?php
#[上传文件管理]
if($sysact == "add_linkok")
{
	$tmpname = $STR->safe($tmpname);
	$fold_file = $STR->safe($fold_file);
	$filename = basename($fold_file);
	if(!$tmpname || !$fold_file || !$filename)
	{
		Error("信息不完整","admin.php?file=upfiles&act=add_link");
	}
	$ext = strtolower(substr($tmpname,-3));
	$ext_f = strtolower(substr($filename,-3));
	if($ext_f != $ext)
	{
		if($ext == ".gz")
		{
			$ext = "tar.gz";
		}
		$tmpname .= ".".$ext_f;
	}
	if($ext_f == "php" || $ext_f == ".js")
	{
		Error("不允许添加 php 和 js 脚本文件","admin.php?file=upfiles&act=add_link");
	}
	$mypath = str_replace($filename,"",$fold_file);
	$postdate = $postdate ? strtotime($postdate) : $system_time;
	$filetype_array = explode(".",$filename);
	$filetype_count = count($filetype_array);
	$filetype = strtolower($filetype_array[$filetype_count-1]);
	$sql = "INSERT INTO ".$prefix."upfiles(filetype,tmpname,filename,folder,postdate) VALUES('".$filetype."','".$tmpname."','".$filename."','".$mypath."','".$postdate."')";
	$DB->qgQuery($sql);
	Error("链接添加成功","admin.php?file=upfiles&act=add_link");
}
elseif($sysAct == "add_xupfiles")
{
	#[启用xupfiles控件进行文件上传]
}
elseif($sysAct == "add_xupfiles_ok")
{
	Error("文件上传成功！","admin.php?file=upfiles&act=add_xupfiles");
}
elseif($sysAct == "upfiles")
{
	#[普通文件上传]
	$up_array = array(1,2,3,4,5,6,7,8,9);
}
elseif($sysAct == "upfilesok")
{
	#[写入数据库中]
	$up_array = array(1,2,3,4,5,6,7,8,9);
	foreach($up_array AS $key=>$value)
	{

		$tmpname = $UP->name("iframeUpload_".$value);#[客户端文件名]
		$setname = SafeHtml($_POST["setname_".$value]);#[设置名称]
		$filename = $UP->up("iframeUpload_".$value,$system_time."_".$value);
		if($filename)
		{
			$extfile = strtolower(substr($filename,-3));
			if($extfile)
			{
				if(strpos("zip,rar,.gz,jpg,gif,png",$extfile) === false)
				{
					if($filename)
					{
						$FS->qgDelete($mypath.$filename);
					}
					Error("附件类仅支持zip、rar、tar.gz、jpg、gif和png六种格式，请不要上传其他类型的附件","admin.php?file=upfiles&act=upfiles");
				}
			}
			else
			{
				if($filename)
				{
					$FS->qgDelete($mypath.$filename);
				}
				Error("附件不允许为空！","admin.php?file=upfiles&act=upfiles");
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
				if(strpos("jpg,gif,png",$extfile) !== false)
				{
					$thumbfile = $GD->thumb($mypath.$filename);
					$markfile = $GD->mark($mypath.$filename);
				}
				else
				{
					$thumfile = "";
					$markfile = "";
				}
				$filetype_array = explode(".",$filename);
				$filetype_count = count($filetype_array);
				$filetype = strtolower($filetype_array[$filetype_count-1]);
				#[上传的文件写入数据库中]
				$sql = "INSERT INTO ".$prefix."upfiles(filetype,tmpname,filename,folder,postdate,thumbfile,markfile) VALUES('".$filetype."','".$tmpname."','".$filename."','".$mypath."','".$system_time."','".$thumbfile."','".$markfile."')";
				$DB->qgQuery($sql);
			}
		}
	}
	Error("附件上传成功！","admin.php?file=upfiles&act=upfiles");
}
elseif($sysact == "delete")
{
	$r_url = $_SESSION["return_url"] ? $_SESSION["return_url"] : "admin.php?file=upfiles&act=list";
	$id = intval($id);
	chk_id($id,$r_url);
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."upfiles WHERE id='".$id."'");
	if(!$rs)
	{
		Error("附件不存在",$r_url);
	}
	if($rs["filename"])
	{
		$FS->qgDelete($rs["folder"].$rs["filename"]);
	}
	if($rs["thumbfile"])
	{
		$FS->qgDelete($rs["folder"].$rs["thumbfile"]);
	}
	if($rs["markfile"])
	{
		$FS->qgDelete($rs["folder"].$rs["markfile"]);
	}
	$DB->qgQuery("DELETE FROM ".$prefix."upfiles WHERE id='".$id."'");
	Error("附件删除成功",$r_url);
}
elseif($sysact == "modifyok")
{
	$return_url = $_SESSION["return_url"] ? $_SESSION["return_url"] : "admin.php?file=upfiles&act=list";
	$id = intval($id);
	chk_id($id,$return_url);
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."upfiles WHERE id='".$id."'");
	if(!$rs)
	{
		Error("附件不存在",$return_url);
	}
	$tmpname = $STR->safe($tmpname);
	if($tmpname == $rs["tmpname"])
	{
		Error("名称一样，不用换名字",$return_url);
	}
	$ext = strtolower(substr($rs["filename"],-3));
	if(strtolower(substr($tmpname,-3)) != $ext)
	{
		if($ext == ".gz")
		{
			$ext = "tar.gz";
		}
		$tmpname .= ".".$ext;
	}
	$DB->qgQuery("UPDATE ".$prefix."upfiles SET tmpname='".$tmpname."' WHERE id='".$id."'");
	Error("数据更新成功",$return_url);
}
elseif($sysAct == "list")
{
	#[显示session里popenValue信息]
	$page_url = "admin.php?file=upfiles&act=list";
	$psize = 30;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	#[显示图片列表]
	$condition = "WHERE 1";
	#
	$keywords = SafeHtml($keywords);
	if($keywords)
	{
		$page_url .= "&keywords=".rawurlencode($keywords);
		$condition .= " AND tmpname LIKE '%".$keywords."%'";
	}
	$get_count = $DB->qgCount("SELECT id FROM ".$prefix."upfiles ".$condition);
	$sql = "SELECT * FROM ".$prefix."upfiles ".$condition." ORDER BY postdate DESC,id DESC LIMIT ".$offset.",".$psize;
	$rslist = $DB->qgGetAll($sql);
	$pagelist = page($page_url,$get_count,$psize,$pageid);#[获取页数信息]
	$filelist = array();
	foreach($rslist AS $key=>$value)
	{
		$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);#[上传时间]
		if(strtolower(substr($value["folder"],0,7)) != "http://" && file_exists($value["folder"].$value["filename"]))
		{
			$filesize = filesize($value["folder"].$value["filename"]);
			if($value["thumbfile"] && file_exists($value["folder"].$value["thumbfile"]))
			{
				$filesize += filesize($value["folder"].$value["thumbfile"]);
				$value["thumb"] = $value["folder"].$value["thumbfile"];
			}
			if($value["markfile"] && file_exists($value["folder"].$value["markfile"]))
			{
				$filesize += filesize($value["folder"].$value["markfile"]);
				$value["mark"] = $value["folder"].$value["markfile"];
			}
			$value["filesize"] = $STR->num_format($filesize);
			#[检测附件是否是图片]
			if(strpos("jpg,gif,png",substr($value["filename"],-3)) !== false)
			{
				$value["thumb"] = $value["thumb"] ? $value["thumb"] : ($value["mark"] ? $value["mark"] : $value["folder"].$value["filename"]);
				$piclist[] = $value;
			}
			else
			{
				$attlist[] = $value;
			}
		}
		else
		{
			$extlist[] = $value;
		}
	}
	$_SESSION["return_url"] = $page_url."&pageid=".$pageid;
}
Foot("upfiles.qg");
?>