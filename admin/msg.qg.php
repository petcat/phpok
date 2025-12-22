<?php
#[引入无限级分类]
require_once("class/unlimited_category.class.php");
$CT = new Category();
$r_url = "admin.php?file=msg";
#[信息录入]
if($sysAct == "add")
{
	if(!$sysgroupid && !$cateid)
	{
		Error("操作不正确，系统无法获取相关信息...","admin.php?file=sysgroup&act=list");
	}
	if($cateid)
	{
		$rs = $DB->qgGetOne("SELECT sysgroupid FROM ".$prefix."category WHERE id='".$cateid."' AND language='".$language."'");
		if(!$rs)
		{
			Error("分类ID不正确！","admin.php?file=sysgroup&act=list");
		}
		$sysgroupid = $rs["sysgroupid"];
	}
	#[判断权限]
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$group = $DB->qgGetOne("SELECT * FROM ".$prefix."sysgroup WHERE id='".$sysgroupid."'");
	$rslist = $DB->qgGetAll("SELECT id,catename,rootid,parentid FROM ".$prefix."category WHERE sysgroupid='".$sysgroupid."' AND language='".$language."'");
	$catelist = $CT->arraySet($rslist,0);
	unset($rslist);
	$csslist = csslist();
	$num_array = $field_array = array(0,1,2,3,4,5,6,7,8,9);
	$fckeditor = FckEditor("content","","LongDefault","400px","100%");
	$htmlfolder = "/html/".date("Ym/d/",$system_time);

	Foot("msg.add.qg");
}
elseif($sysAct == "addok")
{
	$sysgroupid = intval($sysgroupid);
	#[判断权限]
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$cateid = intval($cateid);
	if(!$cateid)
	{
		Error("请选择分类...","admin.php?file=msg&act=add&sysgroupid=".$sysgroupid);
	}
	$subject = $STR->safe($subject);
	if(!$subject)
	{
		Error("主题不允许为空...","admin.php?file=msg&act=add&cateid=".$cateid);
	}
	$style = $STR->safe($style);#[样式]
	$author = $STR->safe($author);#[作者，即发布人]
	if($author == $_SESSION["admin"]["user"])
	{
		$authorid = $_SESSION["admin"]["id"];
	}
	else
	{
		if($author)
		{
			$admin_id = $DB->qgGetOne("SELECT id FROM ".$prefix."user WHERE username='".$author."'");
			if($admin_id)
			{
				$authorid = intval($admin_id["id"]);
			}
			else
			{
				$authorid = 0;
			}
		}
		else
		{
			$author = "admin";
			$authorid = 0;
		}
	}
	$postdate = $postdate ? strtotime($postdate) : $system_time;#[发布时间]
	$modifydate = $system_time;#[修改时间，使用系统时间]
	$thumb = intval($thumb);#[缩略图ID号]
	$tplfile = $STR->safe($tplfile);#[自定义模板]
	$hits = intval($hits);
	$orderdate = $postdate;
	#[设置置顶、推荐、精华]
	$istop = isset($istop) ? 1 : 0;
	$isvouch = isset($isvouch) ? 1 : 0;
	$isbest = isset($isbest) ? 1 : 0;
	$ext_url= $STR->safe($ext_url);
	$ext_docket = $STR->safe($ext_docket);
	$ext_marketprice = $STR->safe($ext_marketprice);
	$ext_shopprice = $STR->safe($ext_shopprice);
	$ext_standard = $STR->safe($ext_standard);
	$ext_number = $STR->safe($ext_number);
	$ext_size = $STR->safe($ext_size);
	$ext_0 = $STR->safe($ext_0);
	$ext_1 = $STR->safe($ext_1);
	$ext_2 = $STR->safe($ext_2);
	$ext_3 = $STR->safe($ext_3);
	$ext_4 = $STR->safe($ext_4);
	$ext_5 = $STR->safe($ext_5);
	$ext_6 = $STR->safe($ext_6);
	$ext_7 = $STR->safe($ext_7);
	$ext_8 = $STR->safe($ext_8);
	$ext_9 = $STR->safe($ext_9);
	$ifcheck = intval($ifcheck);
	$sql = "INSERT INTO ".$prefix."msg(cateid,subject,style,author,authorid,postdate,modifydate,thumb,tplfile,hits,orderdate,istop,isvouch,isbest,ext_url,ext_docket,ext_marketprice,ext_shopprice,ext_standard,ext_number,ext_size,ext_0,ext_1,ext_2,ext_3,ext_4,ext_5,ext_6,ext_7,ext_8,ext_9,ifcheck) VALUES('".$cateid."','".$subject."','".$style."','".$author."','".$authorid."','".$postdate."','".$modifydate."','".$thumb."','".$tplfile."','".$hits."','".$orderdate."','".$istop."','".$isvouch."','".$isbest."','".$ext_url."','".$ext_docket."','".$ext_marketprice."','".$ext_shopprice."','".$ext_standard."','".$ext_number."','".$ext_size."','".$ext_0."','".$ext_1."','".$ext_2."','".$ext_3."','".$ext_4."','".$ext_5."','".$ext_6."','".$ext_7."','".$ext_8."','".$ext_9."','".$ifcheck."')";
	#[注销变量]
	unset($subject,$style,$author,$authorid,$postdate,$modifydate,$thumb,$tplfile,$hits);
	unset($orderdate,$istop,$isvouch,$isbest,$ext_url,$ext_docket,$ext_marketprice,$ext_shopprice);
	unset($ext_standard,$ext_number,$ext_size,$ext_0,$ext_1,$ext_2,$ext_3,$ext_4,$ext_5,$ext_6);
	unset($ext_7,$ext_8,$ext_9,$ifcheck);
	$insert_id = $DB->qgInsert($sql);
	unset($sql);
	$content = $STR->html($content);
	$sql = "INSERT INTO ".$prefix."msg_content(id,cateid,content) VALUES('".$insert_id."','".$cateid."','".$content."')";
	$DB->qgQuery($sql);
	unset($sql);
	Error("信息添加成功...","admin.php?file=msg&act=add&sysgroupid=".$sysgroupid."&cateid=".$cateid);
}
elseif($sysAct == "modify")
{
	$id = intval($id);
	if(!$id || $id == 0)
	{
		Error("操作不正确，无法取得ID号信息...","admin.php?file=sysgroup&act=list");
	}
	$rs = $DB->qgGetOne("SELECT * FROM ".$prefix."msg WHERE id='".$id."'");
	$rsC = $DB->qgGetOne("SELECT * FROM ".$prefix."msg_content WHERE id='".$id."'");
	$content = FckToHtml($rsC["content"]);
	$cateid = $rs["cateid"];
	$rsG = $DB->qgGetOne("SELECT sysgroupid FROM ".$prefix."category WHERE id='".$cateid."'");
	if(!$rsG)
	{
		Error("分类ID不正确！","admin.php?file=sysgroup&act=list");
	}
	$sysgroupid = $rsG["sysgroupid"];
	#[判断权限]
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$group = $DB->qgGetOne("SELECT * FROM ".$prefix."sysgroup WHERE id='".$sysgroupid."'");
	$rslist = $DB->qgGetAll("SELECT id,catename,rootid,parentid FROM ".$prefix."category WHERE sysgroupid='".$sysgroupid."' AND language='".$language."'");
	$catelist = $CT->arraySet($rslist,0);
	$csslist = csslist();
	$num_array = $field_array = array(0,1,2,3,4,5,6,7,8,9);
	$fckeditor = FckEditor("content",$content,"LongDefault","400px","100%");
	Foot("msg.modify.qg");
}
elseif($sysAct == "modifyok")
{
	$id = intval($id);
	if(!$id || $id == 0)
	{
		Error("操作不正确，无法取得ID号信息...","admin.php?file=sysgroup&act=list");
	}
	$cateid = intval($cateid);
	$sysgroupid = intval($sysgroupid);
	#[判断权限]
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$cateid = intval($cateid);
	$old_rs = $DB->qgGetOne("SELECT * FROM ".$prefix."msg WHERE id='".$id."'");
	if(!$cateid)
	{
		$cateid = $old_rs["cateid"];
	}
	$subject = $STR->safe($subject);
	if(!$subject)
	{
		$subject = $old_rs["subject"];
	}
	$style = $STR->safe($style);#[样式]
	$author = $STR->safe($author);#[作者，即发布人]
	if($author == $_SESSION["admin"]["user"])
	{
		$authorid = $_SESSION["admin"]["id"];
	}
	else
	{
		if($author)
		{
			$admin_id = $DB->qgGetOne("SELECT id FROM ".$prefix."user WHERE username='".$author."'");
			$authorid = intval($admin_id["id"]);
		}
		else
		{
			$authorid = $old_rs["authorid"];
			$author = $old_rs["author"];
		}
	}
	$postdate = $postdate ? strtotime($postdate) : $old_rs["postdate"];#[发布时间]
	$modifydate = $system_time;#[修改时间]
	$thumb = intval($thumb);#[缩略图ID号]
	$tplfile = $STR->safe($tplfile);#[自定义模板]
	$hits = intval($hits);
	$orderdate = $postdate;
	#[设置置顶、推荐、精华]
	$istop = isset($istop) ? 1 : 0;
	$isvouch = isset($isvouch) ? 1 : 0;
	$isbest = isset($isbest) ? 1 : 0;
	$ext_url= $STR->safe($ext_url);
	$ext_docket = $STR->safe($ext_docket);
	$ext_marketprice = $STR->safe($ext_marketprice);
	$ext_shopprice = $STR->safe($ext_shopprice);
	$ext_standard = $STR->safe($ext_standard);
	$ext_number = $STR->safe($ext_number);
	$ext_size = $STR->safe($ext_size);
	$ext_0 = $STR->safe($ext_0);
	$ext_1 = $STR->safe($ext_1);
	$ext_2 = $STR->safe($ext_2);
	$ext_3 = $STR->safe($ext_3);
	$ext_4 = $STR->safe($ext_4);
	$ext_5 = $STR->safe($ext_5);
	$ext_6 = $STR->safe($ext_6);
	$ext_7 = $STR->safe($ext_7);
	$ext_8 = $STR->safe($ext_8);
	$ext_9 = $STR->safe($ext_9);
	$ifcheck = intval($ifcheck);
	$sql = "UPDATE ".$prefix."msg SET cateid='".$cateid."',subject='".$subject."',style='".$style."',author='".$author."',authorid='".$authorid."',postdate='".$postdate."',modifydate='".$modifydate."',thumb='".$thumb."',tplfile='".$tplfile."',hits='".$hits."',istop='".$istop."',isvouch='".$isvouch."',isbest='".$isbest."',ext_url='".$ext_url."',ext_docket='".$ext_docket."',ext_marketprice='".$ext_marketprice."',ext_shopprice='".$ext_shopprice."',ext_standard='".$ext_standard."',ext_number='".$ext_number."',ext_size='".$ext_size."',ext_0='".$ext_0."',ext_1='".$ext_1."',ext_2='".$ext_2."',ext_3='".$ext_3."',ext_4='".$ext_4."',ext_5='".$ext_5."',ext_6='".$ext_6."',ext_7='".$ext_7."',ext_8='".$ext_8."',ext_9='".$ext_8."',ifcheck='".$ifcheck."' WHERE id='".$id."'";
	$content = $STR->html($content);
	$DB->qgQuery($sql);#[写入数据库]
	$DB->qgQuery("UPDATE ".$prefix."msg_content SET cateid='".$cateid."',content='".$content."' WHERE id='".$id."'");
	Error("信息编辑成功...","admin.php?file=msg&act=list&sysgroupid=".$sysgroupid."&cateid=".$cateid);
}
elseif($sysAct == "plset")
{
	$cateid = intval($cateid);
	$sysgroupid = intval($sysgroupid);
	#[判断权限]
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	$myidlist = $STR->safe($idlist);#[获取IDlist]
	if(!$myidlist)
	{
		Error("信息操作不正确","admin.php?file=msg&act=list&sysgroupid=".$sysgroupid);
	}
	$qgtype = $STR->safe($qgtype);
	if($qgtype == "delete")
	{
		$idarray = explode(",",$myidlist);
		foreach($idarray AS $key=>$value)
		{
			$value = intval($value);
			if($value)
			{
				#[检测标签数]
				$msgRs = $DB->qgGetOne("SELECT * FROM ".$prefix."msg WHERE id='".$value."'");
				$cateid = $msgRs["cateid"];
				$DB->qgQuery("DELETE FROM ".$prefix."msg WHERE id='".$value."'");#[删除主题]
				$DB->qgQuery("DELETE FROM ".$prefix."msg_content WHERE id='".$value."'");#[删除内容]
			}
		}
		Error("批量删除主题完成！","admin.php?file=msg&act=list&sysgroupid=".$sysgroupid."&cateid=".$cateid);
	}
	else
	{
		$sql = "UPDATE ".$prefix."msg SET ";
		switch ($qgtype)
		{
			case "top":
				$sql .= "istop='1'";
				$tmsg = "置顶";
			break;
			case "vouch":
				$sql .= "isvouch='1'";
				$tmsg = "推荐";
			break;
			case "best":
				$sql .= "isbest='1'";
				$tmsg = "精华";
			break;
			case "dtop":
				$sql .= "istop='0'";
				$tmsg = "取消置顶";
			break;
			case "dvouch":
				$sql .= "isvouch='0'";
				$tmsg = "取消推荐";
			break;
			case "dbest":
				$sql .= "isbest='0'";
				$tmsg = "取消精华";
			break;
			case "check":
				$sql .= "ifcheck='1'";
				$tmsg = "审核";
			break;
			case "dcheck":
				$sql .= "ifcheck='0'";
				$tmsg = "未审核";
			break;
			case "orderdate":
				$sql .= "orderdate='".$system_time."'";
				$tmsg = "排序提前";
			break;
			default :
				$sql .= "isbest='1'";
				$tmsg = "精华";
			break;
		}
		$sql .= " WHERE id in(".$myidlist.")";
		$DB->qgQuery($sql);
		Error("批量 <span style='color:red;'>".$tmsg."</span> 操作完成！","admin.php?file=msg&act=list&sysgroupid=".$sysgroupid."&cateid=".$cateid);
	}
}
else
{
	$sysgroupid = intval($sysgroupid);
	if(!$sysgroupid)
	{
		Error("操作非法","admin.php?file=sysgroup&act=list");
	}
	#[判断权限]
	if($_SESSION["admin"]["typer"] != "system" && !$QG_AP["msg_".$sysgroupid])
	{
		Error("对不起，您没有权限操作当前功能","admin.php?file=index");
	}
	#[显示系统组名称]
	$sql = "SELECT groupname FROM ".$prefix."sysgroup WHERE id='".$sysgroupid."'";
	$rs = $DB->qgGetOne($sql);
	if(!$rs)
	{
		Error("找不到相关系统组","admin.php?file=sysgroup&act=list");
	}
	$groupname = $rs["groupname"];

	#[根据系统组显示名称]
	$sql = "SELECT id,catename,rootid,parentid FROM ".$prefix."category WHERE sysgroupid='".$sysgroupid."' AND language='".$language."'";
	$catelist = $DB->qgGetAll($sql);
	if(!$catelist || !is_array($catelist) || count($catelist)<1)
	{
		Error("暂无分类，请先添加分类....",$r_url."&act=add&sysgroupid=".$sysgroupid);
	}
	#[得到临时分类组合，基于分类ID]
	foreach($catelist AS $key=>$value)
	{
		$cateid_array[$value["id"]] = $value["id"];
		$tmp_catename[$value["id"]] = $value["catename"];
	}
	$catelist = $CT->arraySet($catelist,0);#[这里的catelist仅用于显示select框]
	#[设置可选]
	$cateid = intval($cateid);
	#[得到cateid_array]
	if($cateid)
	{
		$cateid_array = array($cateid=>$cateid);
		$cateid_array = get_son_id_array($cateid,$catelist,$cateid_array);
	}
	if(count($cateid_array)<1)
	{
		Error("操作有错误","admin.php?file=sysgroup&act=list");
	}
	$page_url = $r_url."&act=list&sysgroupid=".$sysgroupid."&cateid=".$cateid;
	$condition = "WHERE m.cateid=c.id AND c.language='".$language."'";
	$condition .= " AND m.cateid IN(".implode(",",$cateid_array).")";
	$rs_cate = array();
	$condition .= " AND c.sysgroupid='".$sysgroupid."'";

	#[获取关键字]
	$keywords = $STR->safe($keywords);
	$keywords = trim($keywords);
	if($keywords)
	{
		$keywords_array = explode(" ",$keywords);
		$keywords_count = count($keywords_array);
		if($keywords_count > 1)
		{
			$c_array = array();
			foreach($keywords_array AS $key=>$value)
			{
				$value = trim($value);
				if($value)
				{
					$value = str_replace("*","%",$value);
					$c_array[] = "m.subject LIKE '%".$value."%'";
				}
			}
			if(count($c_array)>0)
			{
				$condition .= " AND (".implode(" OR ",$c_array).")";
				$page_url .= "&keywords=".rawurlencode($keywords);
			}
		}
		elseif($keywords_count == 1)
		{
			$keywords = str_replace("*","%",$keywords);
			$condition .= " AND m.subject LIKE '%".$keywords."%'";
			$page_url .= "&keywords=".rawurlencode($keywords);
		}
		unset($keywords_count,$keywords_array);
	}
	#[获取时间段]
	$s_time = $STR->safe($s_time);
	$e_time = $STR->safe($e_time);
	if($s_time)
	{
		$start_time = strtotime($s_time);
		$condition .= " AND m.postdate>='".$start_time."'";
		$page_url .= "&s_time=".rawurlencode($s_time);
	}
	if($e_time)
	{
		$end_time = strtotime($e_time);
		$condition .= " AND m.postdate<='".$end_time."'";
		$page_url .= "&e_time=".rawurlencode($e_time);
	}
	#[获取是否审核]
	$ifcheck = intval($ifcheck);
	if($ifcheck)
	{
		if($ifcheck == 1)
		{
			$condition .= " AND m.ifcheck='1'";
			$page_url .= "&ifcheck=1";
		}
		else
		{
			$condition .= " AND m.ifcheck!=1";
			$page_url .= "&ifcheck=2";
		}
	}
	$psize = 30;
	$pageid = intval($pageid);
	$offset = $pageid>0 ? ($pageid-1)*$psize : 0;
	#[获取个数]
	$sql = "SELECT count(m.id) FROM ".$prefix."msg AS m,".$prefix."category AS c ".$condition;
	$msg_count = $DB->qg_count($sql);
	$pagelist = page($page_url,$msg_count,$psize,$pageid);#[获取页数信息]
	#[加条件]
	$sql = "SELECT m.id FROM ".$prefix."msg AS m,".$prefix."category AS c ".$condition." ORDER BY m.id DESC LIMIT ".$offset.",1";
	$rs_id = $DB->qgGetOne($sql);
	$get_id = $rs_id["id"];
	$condition .= " AND m.id<='".$get_id."'";

	$sql = "SELECT m.id,m.cateid,m.subject,m.author,m.postdate,m.modifydate,m.orderdate,m.thumb,m.ifcheck,m.istop,m.isvouch,m.isbest,m.hits,c.catename FROM ".$prefix."msg AS m,".$prefix."category AS c ".$condition." ORDER BY m.istop DESC,m.orderdate DESC,m.id DESC LIMIT ".$psize;
	$rslist = $DB->qgGetAll($sql,true);
	foreach($rslist AS $key=>$value)
	{
		$value["postdate"] = date("Y-m-d H:i:s",$value["postdate"]);
		$value["modifydate"] = date("Y-m-d H:i:s",$value["modifydate"]);
		$value["v_catename"] = $STR->cut($value["catename"],8,"");
		$msglist[] = $value;
	}
	Foot("msg.list.qg");
}

function get_son_id_array($id,$array,$myid=array())
{
	foreach($array AS $key=>$value)
	{
		if($value["parentid"] == $id)
		{
			$myid[$value["id"]] = $value["id"];
			get_son_id_array($value["id"],$array,$myid);
		}
	}
	return $myid;
}
?>