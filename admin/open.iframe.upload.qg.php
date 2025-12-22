<?php
#[上传文件框架]
/*$system["attachType"] = "jpg,gif,png";

include_once("./class/upload.class.php");
$UP = new UPLOAD("./upfiles/",$system["attachType"]);
*/
if(empty($act))
{
	$act == "add";
}
if(empty($var))
{
	$var = "content";
}
if(empty($form))
{
	$form = "form";
}
if($act == "add")
{
	#[开始上传信息]
	#[上传的效果请到模板里设置]
}
elseif($act == "uploadOK")
{
	$filename = $UP->up("iframeUpload",$system_time."_".rand(0,100));
	if(strpos(",jpg,gif,png,",",".substr($filename,-3).",") !== false)
	{
		#[获取当前服务器信息]
		$myurl = "http://".str_replace("http://","",$_SERVER["SERVER_NAME"]);
		$docu = $_SERVER["PHP_SELF"];
		$array = explode("/",$docu);
		$count = count($array);
		if($count>1)
		{
			foreach($array AS $key=>$value)
			{
				$value = trim($value);
				if($value)
				{
					if(($key+1) < $count)
					{
						$myurl .= "/".$value;
					}
				}
			}
		}
		$myurl .= "/";
		$mypath = $UP->getpath();
		$myurl .= $mypath;
		#[生成缩略图]
		$thumbfile = $GD->thumb($mypath.$filename,$system["gdThumbWidth"],$system["gdThumbHeight"]);
		#[生成水印图]
		$markfile = $GD->mark($mypath.$filename,$system["gdMarkWidth"],$system["gdMarkHeight"]);
	}
	if($filename)
	{
		#[写入数据库中]
		$tmpname = $UP->name("iframeUpload");#[客户端文件名]
		#[上传的文件写入数据库中]
		$DB->qgQuery("INSERT INTO ".$prefix."upfiles(tmpname,filename,folder,postdate,thumbfile,markfile) VALUES('".$tmpname."','".$filename."','".$mypath."','".$system_time."','".$thumbfile."','".$markfile."')");
	}
}
$TPL->set_file("open.iframe.upload.qg");
$TPL->n();
$TPL->p("open.iframe.upload.qg");
exit;
?>