<?php
#[投票页面]
require_once("global.php");
$id = intval($id);
#[如果不存在ID时，系统提示错误]
if(empty($id))
{
	Error($langs["vote_emptyid"],"home.php");
}
$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."vote WHERE id='".$id."' AND voteid='0' AND language='".LANGUAGE_ID."'");
if(empty($rs))
{
	Error($langs["vote_emptyrs"],"home.php");
}
#[判断是否已投票]
$isvote = false;
if($_COOKIE["voteid"] && $_COOKIE["voteid"] == $id)
{
	$isvote = true;
}

$vote_subject = $rs["subject"];
$vote_type = $rs["vtype"];
unset($rs);
#[判断是否已经投票了]
if($act == "submit")
{
	if($isvote)
	{
		Error($langs["vote_isvotetrue"],"vote.php?id=".$id."&act=view");
	}
	#[增加统计]
	$ifvote_status = false;
	if($vote_type == "single")
	{
		$voteid = intval($voteid);#[投票ID]
		$DB->qgQuery("UPDATE ".$prefix."vote SET vcount=vcount+1 WHERE id='".$voteid."' AND voteid='".$id."'");
		$ifvote_status = true;
	}
	else
	{
		$array = array();
		foreach((is_array($voteid) ? $voteid : $array) AS $key=>$value)
		{
			$value = intval($value);
			if($value)
			{
				$DB->qgQuery("UPDATE ".$prefix."vote SET vcount=vcount+1 WHERE id='".$value."' AND voteid='".$id."'");
				$ifvote_status = true;
			}
		}
	}
	#[写入Cookie信息]
	if($ifvote_status)
	{
		setcookie("voteid",$id);
		Error($langs["vote_submitok"],"vote.php?id=".$id."&act=view");
	}
}
#[获取投票总人数]
$vote_width = 500;
$total_count = $DB->qgGetOne("SELECT sum(vcount) AS qgcount FROM ".$prefix."vote WHERE voteid='".$id."'");
$total_count = $total_count["qgcount"];
$rs = $DB->qgGetAll("SELECT id,subject,vcount,ifcheck FROM ".$prefix."vote WHERE voteid='".$id."' ORDER BY id ASC");
$vote_list = array();
foreach($rs AS $key=>$value)
{
	if($total_count>1)
	{
		$value["bili"] = round($value["vcount"]/$total_count,4);#[设置比例]
	}
	else
	{
		$vale["bili"] = "0%";
	}
	$value["img_width"] = $value["bili"] * $vote_width;
	$value["bili"] = $value["bili"] * 100;#[显示百分比]
	if($vote_type == "pl")
	{
		$value["vote_input"] = "<input type='checkbox' name='voteid[]' value='".$value["id"]."'";
	}
	else
	{
		$value["vote_input"] = "<input type='radio' name='voteid' value='".$value["id"]."'";
	}
	if($value["ifcheck"])
	{
		$value["vote_input"] .= " checked";
	}
	$value["vote_input"] .= ">";
	$vote_list[] = $value;
}

#[定义题头]
$sitetitle = $langs["vote_subjectpre"].$vote_subject." - ".$system["sitename"];

#[导航栏]
$lead_menu[0]["url"] = "vote.php?id=".$id;
$lead_menu[0]["name"] = $langs["vote_subjectpre"].$vote_subject;
HEAD();
FOOT("vote");
?>