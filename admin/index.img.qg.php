<?php
#[首页前台图片播放管理]
#[判断权限]
if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["picplay"])
{
	Error("对不起，您没有权限操作当前功能","admin.php?file=index");
}
$qgarray = array(1,2,3,4,5,6,7,8,9);#[数组]
if($sysAct == "set")
{
	$imgarray = $urlarray = array();
	$content = $FS->qgRead("data/pic_player_".$language.".php");#[读取图片信息]
	if($content)
	{
		$g_array = explode("\n",$content);
		$g_count = count($g_array);
		if($g_count>0)
		{
			foreach($g_array AS $key=>$value)
			{
				$m = $key+1;
				$value = trim($value);
				$v = explode("|||",$value);
				$imgarray[$m] = $v[0];
				$urlarray[$m] = $v[1];
			}
		}
	}
	Foot("index.img.qg");
}
elseif($sysAct == "setok")
{
	$sql_array = array();
	foreach($qgarray AS $key=>$value)
	{
		$my_img = $my_url = "";
		$my_img = SafeHtml($_POST["img_".$value]);
		$my_url = SafeHtml($_POST["url_".$value]);
		if($my_img)
		{
			$sql_array[$key] = $my_img."|||".$my_url;
		}
	}
	$content = implode("\n",$sql_array);
	$FS->qgWrite($content,"data/pic_player_".$language.".php");#[存放图片轮播]
	Error("图片播放器信息已经更新完毕！","admin.php?file=index.img&act=set");
}
?>