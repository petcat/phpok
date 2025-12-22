<?php
#[附件上传]
include("./class/xu.class.php");
$myUpload = new XUpload_class;

$myUpload->SetOverlayMode(true); // 覆盖同名文件
$myUpload->InitParameters();

if ($myUpload->IsUploadFile())
{
	$filename = $myUpload->CreateFileName("./upfiles/xu_temp","",$_POST['xu_filename']);

	if ($filename != "")
	{
		$filename = $myUpload->SaveToFile($filename);
	}
}
$list = array();
$list = $FS->qgDir("upfiles/xu_temp/");
foreach($list AS $key=>$value)
{
	$value = trim($value);
	if($value)
	{
		$tmpname = basename($value);
		$extfile = substr($value,-3);
		$extfile = strtolower($extfile);
		if($extfile == ".gz")
		{
			$extfile = "tar.gz";
		}

		$filename = $system_time."_".rand(0,100).".".$extfile;
		$mypath = "upfiles/".date("Ym/d/",$system_time);

		$FS->qgMove($value,$mypath.$filename);

		if(strpos("jpg,gif,png",$extfile) !== false)
		{
			#[生成缩略图]
			$thumbfile = $GD->thumb($mypath.$filename);
			#[生成水印图]
			$markfile = $GD->mark($mypath.$filename);
		}
		else
		{
			$thumfile = "";
			$markfile = "";
		}

		$tmpname = $tmpname ? $tmpname : $filename;
		#[将tmp信息转为UTF8编码]
		$tmpname = gb2utf8($tmpname);
		$filetype_array = explode(".",$filename);
		$filetype_count = count($filetype_array);
		$filetype = strtolower($filetype_array[$filetype_count-1]);
		$sql = "INSERT INTO ".$prefix."upfiles(filetype,tmpname,filename,folder,postdate,thumbfile,markfile) VALUES('".$filetype."','".$tmpname."','".$filename."','".$mypath."','".$system_time."','".$thumbfile."','".$markfile."')";
		$DB->qgQuery($sql);
	}
}

$myUpload->Out("PHPOK!");
exit;
?>