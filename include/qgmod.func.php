<?php
#============================
#	Filename: qgmod.func.php
#	Note	: 模块管理
#	Version : 2.0
#	Author  : qinggan
#	Update  : 2008-03-04
#============================
function QGMOD_ONLINE()
{
	global $FS;
	$online = $FS->qgRead("data/online_help.php");
	return $online;
}

function QGMOD_SPECIAL($special_id,$special_length=0,$iscache=true)
{
	include("include/module/special.qgmod.php");
	return $special;
}

function QGMOD_PLAYER()
{
	include("include/module/picplayer.qgmod.php");
	return $playerlist;
}

function QGMOD_NOTICE($title_length=0,$msg_length=0,$limit=10,$postdate="m-d",$iscache=true)
{
	include("include/module/notice.qgmod.php");
	return $notice;
}

function QGMOD_VOTE($vote_id,$iscache=true)
{
	include("include/module/vote.qgmod.php");
	return $vote;
}

function QGMOD_MSGLIST($cateid,$length=0,$orderby="",$ispic=false,$limit=10,$iscache=true)
{
	include("include/module/msglist.qgmod.php");
	return $list;
}

function QGMOD_MSGLIST_ALL($sign="new",$type="article",$length=0,$limit=10,$iscache=true)
{
	include("include/module/msglist_all.qgmod.php");
	return $list;
}

function QGMOD_PICLIST_ALL($sign="new",$type="picture",$limit=10,$iscache=true)
{
	include("include/module/piclist_all.qgmod.php");
	return $list;
}

function QGMOD_LIST_IDIN($orderby="",$length=80,$ispic=false,$limit=10,$iscache=true)
{
	include("include/module/list_idin.qgmod.php");
	return $list;
}

function QGMOD_MSG_CATEID($orderby="",$length=80,$ispic=false,$limit=10,$iscache=true)
{
	include("include/module/msg_cateid.qgmod.php");
	return $list;
}

function QGMOD_LINK($type="PIC",$iscache=true)
{
	include("include/module/link.qgmod.php");
	return $list;
}

function QGMOD_AD($id,$iscache=true)
{
	include("include/module/ad.qgmod.php");
	return $ad;
}
#[专题分类列表]
function QGMOD_SPECIAL_LIST($groupid,$length=0,$count=15,$iscache=true)
{
	include("include/module/spelist.qgmod.php");
	return $spelist;
}
#[四大模块分类列表]
function QGMOD_CATELIST($inid,$iscache=true)
{
	include("include/module/catelist.qgmod.php");
	return $catelist;
}

#[根据父类ID，得到下一级的子类ID列表]
function QGMOD_SONCATE($cateid,$iscache=true)
{
	include("include/module/soncate.qgmod.php");
	return $catelist;
}

#[页脚统计]
function QGMOD_FOOT()
{
	global $DB,$FS;
	$end_time = explode(" ",microtime());
	$end_time = $end_time[0] + $end_time[1];
	$time_used = round($end_time - START_TIME,5);#[计算消耗时间]
	unset($end_time);
	$sqlCount = $DB->queryCount;
	$fileCount = $FS->readCount;
	$sql_count = $sqlCount > 1 ? $sqlCount." queries" : $sqlCount." query";
	$file_count = $fileCount > 1 ? $fileCount." files" : $fileCount." file";
	unset($DB,$FS,$sqlCount,$fileCount);
	$debugmsg = "Processed in ".$time_used." second(s), ".$sql_count.", ".$file_count;
	return $debugmsg;
}

#[以下函数仅供内部使用]
function _____QGMODULE_CLEARUP_LIST($rslist,$length=0,$ispic=false)
{
	foreach($rslist AS $key=>$value)
	{
		$value["cut_subject"] = $length>0 ? CutString($value["subject"],$length,"…") : $value["subject"];
		if($value["thumb"] && $ispic)
		{
			if($value["u_thumbfile"] && file_exists($value["u_folder"].$value["u_thumbfile"]))
			{
				$value["thumb"] = $value["u_folder"].$value["u_thumbfile"];
			}
			else
			{
				if(file_exists($value["u_folder"].$value["u_markfile"]))
				{
					$value["thumb"] = $value["u_folder"].$value["u_markfile"];
				}
				else
				{
					$value["thumb"] = $value["u_folder"].$value["u_filename"];
				}
			}
		}
		$list[] = $value;
	}
	unset($rslist);
	return $list;
}

function _____QGMODULE_ORDERBY($orderby,$m="m")
{
	if($orderby)
	{
		$order_list = array();
		$array = explode(",",$orderby);
		foreach($array AS $key=>$value)
		{
			$value = trim($value);
			if($value)
			{
				$order_list[] = $m.".".$value;
			}
		}
		if(count($order_list)<1)
		{
			$order_list[0] = $m.".postdate DESC";
			$order_list[1] = $m.".id DESC";
		}
	}
	else
	{
		$order_list[0] = $m.".orderdate DESC";
		$order_list[1] = $m.".id DESC";
	}
	unset($orderby);
	return $order_list;
}
?>