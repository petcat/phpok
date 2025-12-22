<?php
#============================
#	Filename: picplayer.qgmod.php
#	Note	: 图片播放器
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-3-25
#============================
global $FS;
#[图片播放器]
$pic_content = $FS->qgRead("data/pic_player_".LANGUAGE_ID.".php");
$playerlist = array();
if($pic_content)
{
	$pic_array = explode("\n",$pic_content);
	$count = count($pic_array);
	if($count>0)
	{
		$m = 1;
		foreach($pic_array AS $key=>$value)
		{
			$v = explode("|||",$value);
			if($v[0] && $v[1])
			{
				$v[0] = str_replace("\n","",$v[0]);
				$v[1] = str_replace("\n","",$v[1]);
				$temp_array[$m]["img"] = $v[0];
				$temp_array[$m]["url"] = $v[1];
				$m++;
			}
		}
	}
	$playerlist = $temp_array;#[传递变量到自己设置的变量名中]
	unset($temp_array,$pic_content);
	return $playerlist;
}
?>